<?php

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
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id();

            // Relação com a tabela users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Campos da encomenda
            $table->string('tamanhoPizza');
            $table->string('basePizza');
            $table->text('ingredientesExtra')->nullable(); // pode ser null (caso não haja extra)
            $table->string('estadoPedido')->default('pendente'); // estado inicial por default

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomendas');
    }
};

