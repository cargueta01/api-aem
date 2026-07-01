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
        Schema::create('branchs', function (Blueprint $table) {
        $table->id();
        // Relación con Empresa [cite: 15]
        $table->foreignId('enterprise_id')
              ->constrained('enterprises')
              ->onDelete('cascade') 
              ->onUpdate('cascade');

        $table->string('name');
        $table->string('doc_number'); 
        $table->string('municipality_codigo');
        $table->string('branchs_status')->default('active');
        $table->timestamps();

        $table->index('enterprise_id');
        $table->index('branchs_status'); 
        $table->index('doc_number'); 
        $table->index('municipality_codigo');   
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
