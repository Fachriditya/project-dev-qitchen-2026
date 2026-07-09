<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // Buat variabel public agar bisa dibaca oleh file Blade/View
    public $otpCode;

    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Qitchen Anda',
        );
    }

    public function content(): Content
    {
        // Mengarah ke file view emails/otp.blade.php
        return new Content(
            view: 'emails.otp', 
        );
    }
}