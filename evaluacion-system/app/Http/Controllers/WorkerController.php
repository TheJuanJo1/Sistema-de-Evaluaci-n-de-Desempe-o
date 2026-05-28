<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    public function index()
    {
        return view('workers.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('workers.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validación con validación condicional para creación de usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'document_id' => 'required|string|max:20|unique:workers',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'type' => 'required|string',
            'create_user' => 'nullable|boolean',
            'role' => 'required_if:create_user,true|nullable|string|exists:roles,name',
        ]);

        // Si se va a crear usuario, obligar a que haya email
        if ($request->boolean('create_user') && empty($request->email)) {
            return back()->withInput()->withErrors(['email' => 'El correo es obligatorio para crear un usuario del sistema.']);
        }

        $worker = Worker::create($request->only(['name', 'document_id', 'email', 'position', 'type']) + ['is_active' => true]);

        if ($request->boolean('create_user')) {
            $user = User::create([
                'name' => $worker->name,
                'email' => $worker->email,
                'password' => Hash::make($worker->document_id), // Default password is document_id
                'is_active' => true,
            ]);
            $user->assignRole($request->role);
        }

        return redirect()->route('workers.index')->with('status', 'Trabajador registrado exitosamente' . ($request->boolean('create_user') ? ' y usuario creado (Contraseña: '.$worker->document_id.').' : '.'));
    }

    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_id' => 'required|string|unique:workers,document_id,'.$worker->id,
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $worker->update($request->all());

        return redirect()->route('workers.index')->with('status', 'Información del trabajador actualizada.');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle, 1000, ';');

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            // Mapping: Name, Document, Email, Position, Type
            if (count($data) >= 5) {
                Worker::updateOrCreate(
                    ['document_id' => $data[1]],
                    [
                        'name' => $data[0],
                        'email' => $data[2],
                        'position' => $data[3],
                        'type' => $data[4],
                        'is_active' => true,
                    ]
                );
                $count++;
            }
        }

        fclose($handle);

        return back()->with('status', "Se han importado/actualizado {$count} trabajadores exitosamente.");
    }
}
