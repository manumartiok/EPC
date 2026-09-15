<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class InfoPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public UploadedFile $archivo;

    public function __construct(array $data, UploadedFile $archivo)
    {
        $this->data = $data;
        $this->archivo = $archivo;
    }

    public function build()
    {
        return $this->subject('Nuevo comprobante de pago')
            ->view('emails.info-pago')
            ->attach(
                $this->archivo->getRealPath(),
                [
                    'as' => $this->archivo->getClientOriginalName(),
                    'mime' => $this->archivo->getMimeType(),
                ]
            );
    }
}