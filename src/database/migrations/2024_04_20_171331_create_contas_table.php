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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->string('fornecedor');
            $table->string('valor');
            $table->string('descricao')->nullable();
            $table->string('status')->nullable();
            $table->string('tipo');
            $table->string('numero_documento')->nullable();
            $table->date('data_pagamento')->nullable();
            $table->date('data_vencimento');
            $table->string('imagem')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
