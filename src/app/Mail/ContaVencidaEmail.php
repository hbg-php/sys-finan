<?php

namespace App\Mail;

use App\Models\Conta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContaVencidaEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Conta $conta) {}

    public function build()
    {
        return $this->subject('Lembrete de pagamento')
            ->view('emails.conta_vencida');
    }
}
