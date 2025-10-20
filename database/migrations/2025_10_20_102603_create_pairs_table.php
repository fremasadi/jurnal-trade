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
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
                        $table->string('symbol_name', 20)->unique()->comment('e.g. XAUUSD, EURUSD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairs');
    }
};
