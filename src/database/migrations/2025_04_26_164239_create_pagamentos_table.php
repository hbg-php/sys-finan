<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('conta_id');
            $table->string('numero_cartao');
            $table->string('nome_titular_cartao')->comment('Nome do titular do cartão utilizado no pagamento.');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento');
            $table->enum('tipo_pagamento', [1, 2, 3])->comment('1 - PIX, 2 - Boleto, 3 - Cartão');
            $table->enum('status', [1, 2, 3])->comment('1 - Pendente, 2 - Aprovado, 3 - Reprovado');
            $table->text('motivo_recusado')->comment('Caso o status seja recusado, o motivo da recusa')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
