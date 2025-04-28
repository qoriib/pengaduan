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
        Schema::create('request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('resolver_user_id')->constrained('users')->onDelete('cascade');
            $table->date('received_at')->nullable(); // Tgl Terima CAR PAR
            $table->text('temporary_repair')->nullable(); // Tindakan sementara jika ada
            $table->text('cause_analysis'); // Analisa Penyebab
            $table->text('correction_action'); // Tindakan Perbaikan dan Pencegahan
            $table->string('pic'); // PIC
            $table->date('execution_time')->nullable(); // Waktu Pelaksanaan
            $table->string('document_revised')->nullable(); // Dokumen direvisi (TKI, Formulir, dll)
            $table->date('target_verification_date')->nullable(); // Target Waktu Verifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_details');
    }
};
