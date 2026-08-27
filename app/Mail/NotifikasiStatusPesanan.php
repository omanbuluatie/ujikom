<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * UJIKOM — Notifikasi status pesanan via email.
 * Driver `log` (MAIL_MAILER=log): isi terlihat di storage/logs/laravel.log untuk demo tanpa SMTP.
 */
class NotifikasiStatusPesanan extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pesanan $pesanan)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status pesanan '.$this->pesanan->nomor.': '.$this->pesanan->status->label(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.status-pesanan',
        );
    }
}
