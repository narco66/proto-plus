<?php

namespace App\Notifications;

use App\Models\Demande;
use App\Models\WorkflowStepInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeEnAttenteValidation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Demande $demande,
        public WorkflowStepInstance $stepInstance
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Demande en attente de validation - ' . $this->demande->reference)
            ->line('Une nouvelle demande nécessite votre validation.')
            ->line('Référence : ' . $this->demande->reference)
            ->line('Étape : ' . $this->stepInstance->stepDefinition->libelle)
            ->action('Voir la demande', route('workflow.show', $this->demande))
            ->line('Merci d\'utiliser PROTO PLUS !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Demande en attente de validation',
            'message' => 'La demande ' . $this->demande->reference . ' nécessite votre validation.',
            'detail' => 'Étape : ' . $this->stepInstance->stepDefinition->libelle,
            'link' => route('workflow.show', $this->demande),
            'demande_id' => $this->demande->id,
            'step_id' => $this->stepInstance->id,
        ];
    }
}
