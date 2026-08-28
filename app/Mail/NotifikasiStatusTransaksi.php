<?php

namespace App\Mail;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** REVISI — Email log status transaksi (MAIL_MAILER=log). */
class NotifikasiStatusTransaksi extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaksi $transaksi)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status transaksi '.$this->transaksi->kode_transaksi.': '.$this->transaksi->status->label(),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.status-transaksi');
    }
}
