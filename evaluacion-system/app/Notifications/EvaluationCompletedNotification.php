<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Worker;

class EvaluationCompletedNotification extends Notification
{
    use Queueable;

    protected $evaluator;
    protected $worker;

    /**
     * Create a new notification instance.
     *
     * @param  User  $evaluator
     * @param  Worker  $worker
     * @return void
     */
    public function __construct(User $evaluator, Worker $worker)
    {
        $this->evaluator = $evaluator;
        $this->worker = $worker;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // also store in database
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'evaluator_name' => $this->evaluator->name ?? 'Evaluador',
            'evaluator_role' => $this->evaluator->getRoleNames()->first() ?? 'Rol',
            'worker_name' => $this->worker->name ?? 'Trabajador',
            'worker_id' => $this->worker->id ?? null,
            'message' => $this->evaluator->name . ' (' . ($this->evaluator->getRoleNames()->first() ?? 'Rol') . ') completó su evaluación del trabajador ' . $this->worker->name,
        ];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $evaluatorName = $this->evaluator->name ?? 'Evaluador';
        $role = $this->evaluator->getRoleNames()->first() ?? 'Rol';
        $workerName = $this->worker->name ?? 'Trabajador';
        $workerId = $this->worker->id ?? '';

        return (new MailMessage)
            ->subject('Evaluación completada por ' . $role)
            ->greeting('Hola ' . ($notifiable->name ?? ''))
            ->line("{$evaluatorName} ({$role}) ha completado su evaluación del trabajador {$workerName} (ID: {$workerId}).")
            ->action('Ver Evaluaciones', url('/evaluations'))
            ->line('Gracias por su atención.');
    }
}
