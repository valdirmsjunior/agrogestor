<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf_cnpj')->unique();
            $table->string('telefone');
            $table->string('email')->unique();
            $table->string('endereco');
            $table->date('data_cadastro')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtores');
    }
};
