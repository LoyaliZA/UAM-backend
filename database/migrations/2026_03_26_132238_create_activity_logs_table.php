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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Identificador del empleado
            $table->string('employee_identifier')->index(); 
            
            // El tipo de evento
            $table->string('event_type'); 
            
            // Dónde ocurrió
            $table->text('url_or_path')->nullable();
            
            // El título de la pestaña o ventana
            $table->string('window_title')->nullable();
            
            // Campo JSON para datos específicos
            $table->json('payload')->nullable(); 
            
            // IP del equipo
            $table->ipAddress('ip_address')->nullable();
            
            // Timestamps (Hora de México configurada)
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};