<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $resetLink;
    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->resetLink = route('reset-password', ['token' => $token, 'email' => $email]);
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this
            ->subject('Web_Dien_May_HauLongPhat_Tạo Lại Mật Khẩu')
            ->view('ql_dangnhap.SendEmail')
            ->with('resetLink', $this->resetLink);
    }
}