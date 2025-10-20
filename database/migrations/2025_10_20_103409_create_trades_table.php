<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id(); // trade_id

            // 🔗 Relasi ke tabel lain
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->onDelete('set null');
            $table->foreignId('pair_id')->constrained('pairs')->onDelete('cascade');

            // 📈 Informasi trade
            $table->enum('direction', ['Buy', 'Sell']);
            $table->decimal('entry_price', 15, 5);
            $table->decimal('exit_price', 15, 5)->nullable();
            $table->decimal('sl_price', 15, 5)->nullable();
            $table->decimal('tp_price', 15, 5)->nullable();
            $table->decimal('lot_size', 10, 2)->nullable();

            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();

            // 📊 Hasil & analisis
            $table->enum('result', ['Profit', 'Loss'])->nullable();
            $table->decimal('pnl_value', 15, 2)->nullable();
            $table->text('reason_entry')->nullable();
            $table->text('notes')->nullable();

            // 📷 Screenshot trade (opsional)
            $table->string('screenshot_img')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
