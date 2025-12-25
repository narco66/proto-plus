<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeStatutChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Demande $demande,
        public string $action
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
        $messages = [
            'valide' => [
                'subject' => 'Demande validée - ' . $this->demande->reference,
                'line1' => 'Votre demande a été validée avec succès.',
            ],
            'rejete' => [
                'subject' => 'Demande rejetée - ' . $this->demande->reference,
                'line1' => 'Votre demande a été rejetée.',
            ],
            'retour_correction' => [
                'subject' => 'Demande retournée pour correction - ' . $this->demande->reference,
                'line1' => 'Votre demande a été retournée pour correction.',
            ],
        ];

        $message = $messages[$this->action] ?? $messages['valide'];

        return (new MailMessage)
            ->subject($message['subject'])
            ->line($message['line1'])
            ->line('Référence : ' . $this->demande->reference)
            ->action('Voir la demande', route('demandes.show', $this->demande))
            ->line('Merci d\'utiliser PROTO PLUS !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $titles = [
            'valide' => 'Demande validée',
            'rejete' => 'Demande rejetée',
            'retour_correction' => 'Demande retournée pour correction',
        ];

        $messages = [
            'valide' => 'Votre demande ' . $this->demande->reference . ' a été validée avec succès.',
            'rejete' => 'Votre demande ' . $this->demande->reference . ' a été rejetée.',
            'retour_correction' => 'Votre demande ' . $this->demande->reference . ' a été retournée pour correction.',
        ];

        return [
            'title' => $titles[$this->action] ?? 'Changement de statut',
            'message' => $messages[$this->action] ?? 'Le statut de votre demande a changé.',
            'link' => route('demandes.show', $this->demande),
            'demande_id' => $this->demande->id,
            'action' => $this->action,
        ];
    }
}
