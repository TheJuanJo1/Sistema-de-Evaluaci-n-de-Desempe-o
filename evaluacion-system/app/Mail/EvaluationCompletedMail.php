<?php

namespace App\Mail;

use App\Models\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvaluationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evaluation;

    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Evaluación de Desempeño ha sido completada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.evaluation-completed',
        );
    }
}
