<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SPKCreatedNotification extends Notification
{
    use Queueable;

    private $details;


    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // URL untuk persetujuan langsung
        $approveUrl = url('/pengajuan/approve/' . $this->details['id']);

        return (new MailMessage)
            ->subject('SPK Baru Dibuat')
            ->greeting('Halo, Superadmin!')
            ->line('Sebuah SPK baru telah berhasil dibuat.')
            ->line('Nomor Surat: ' . $this->details['nomor_surat'])
            ->line('Tanggal SPK: ' . $this->details['tanggal_spk'])
            ->line('Nama Pihak Kedua: ' . $this->details['nama_pihak_kedua'])
            ->action('Setujui Pengajuan', $approveUrl)
            ->line('Terima kasih telah menggunakan layanan kami!');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
