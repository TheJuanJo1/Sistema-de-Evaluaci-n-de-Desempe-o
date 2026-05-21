<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\SupportTicketMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $talentoHumanoUser = User::role('Talento Humano')->first();
        $targetEmail = $talentoHumanoUser ? $talentoHumanoUser->email : config('mail.from.address', 'soporte@institucion.edu.co');

        try {
            Mail::to($targetEmail)->send(new SupportTicketMail($request->subject, $request->message, auth()->user()));
            return back()->with('status', '¡Mensaje de soporte enviado exitosamente a Talento Humano!');
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un error al intentar enviar el mensaje de soporte. Por favor, inténtalo más tarde.');
        }
    }
}
