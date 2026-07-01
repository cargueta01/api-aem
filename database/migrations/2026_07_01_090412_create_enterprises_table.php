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
        Schema::create('enterprises', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')
              ->constrained('companys')
              ->onDelete('restrict')
              ->onUpdate('cascade');

        $table->string('name');
        $table->string('enterprises_status')->default('active');
        $table->timestamps();

        $table->index('company_id'); 
        $table->index('enterprises_status'); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprises');
    }
};
