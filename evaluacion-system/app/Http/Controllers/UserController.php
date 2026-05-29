<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Worker;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            // Optional fields for worker creation
            'document_id' => ['nullable', 'string', 'max:20', 'unique:workers,document_id'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        // Automatically create a corresponding Worker record for staff roles
        if (in_array($request->role, ['Talento Humano', 'Coordinador de Convivencia', 'Coordinador Académico', 'Rector'])) {
            Worker::create([
                'name' => $request->name,
                'email' => $request->email,
                'document_id' => $request->input('document_id') ?? '',
                'position' => $request->input('position') ?? '',
                'type' => $request->role,
                'is_active' => true,
            ]);
        }

        return redirect()->route('users.index')->with('status', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $worker = \App\Models\Worker::where('email', $user->email)->first();
        // If no worker exists, create an empty instance to avoid type errors
        $worker = $worker ?? new \App\Models\Worker();
        return view('users.edit', compact('user', 'roles', 'worker'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,name'],
            // Campos adicionales para sincronizar con Worker
            'document_id' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        // Sincronizar datos con el modelo Worker (o crear si no existe)
        $worker = \App\Models\Worker::where('email', $user->email)->first();
        if ($worker) {
            $worker->update([
                'document_id' => $request->input('document_id', $worker->document_id),
                'position' => $request->input('position', $worker->position),
                'type' => $request->input('type', $worker->type),
            ]);
        } else {
            \App\Models\Worker::create([
                'name' => $user->name,
                'email' => $user->email,
                'document_id' => $request->input('document_id', ''),
                'position' => $request->input('position', ''),
                'type' => $request->input('type', ''),
                'is_active' => true,
            ]);
        }

        return redirect()->route('users.index')->with('status', 'Usuario actualizado exitosamente.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activado' : 'desactivado';

        return back()->with('status', "Usuario {$status} exitosamente.");
    }
}
