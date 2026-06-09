<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $pedido;
    public string $destinatarioTipo;
    public $archivo;

    public function __construct(array $pedido, string $destinatarioTipo = 'cliente', $archivo = null)
    {
        $this->pedido = $pedido;
        $this->destinatarioTipo = $destinatarioTipo;
        $this->archivo = $archivo;
    }

    public function build()
    {
        $subject = $this->destinatarioTipo === 'duenos'
            ? 'Nuevo pedido web recibido'
            : 'Confirmacion de pedido';

        $mail = $this->subject($subject)
            ->view('emails.pedido-confirmacion');

        if ($this->archivo) {
            $mail->attach($this->archivo->getRealPath(), [
                'as' => $this->archivo->getClientOriginalName(),
                'mime' => $this->archivo->getMimeType(),
            ]);
        }

        return $mail;
    }
}
