<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\ContaVencidaEmail;
use App\Models\Conta;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

final class EnviarEmailsContasVencidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-emails-contas-vencidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia e-mails quando a data de pagamento chega';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hoje = Carbon::today();
        $contas = Conta::with('user')->whereDate('data_vencimento', $hoje)->get();

        foreach ($contas as $conta) {
            Mail::to($conta->user->email)->queue(new ContaVencidaEmail($conta));
        }
        $this->info('E-mails enviados com sucesso.');
    }
}
