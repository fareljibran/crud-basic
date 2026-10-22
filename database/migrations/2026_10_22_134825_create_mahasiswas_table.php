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
    Schema::create('mahasiswas', function (Blueprint $table) {
        $table->id(); // Primary key, auto-increment (int)
        $table->string('nama', 100);
        $table->string('jenis_kelamin', 10);
        $table->string('tempat_lhr', 20);
        $table->date('tanggal_lhr');
        $table->string('alamat', 200)->nullable(); // Nullable sesuai instruksi
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
