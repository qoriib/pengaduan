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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('no_form'); // No. PND646000/YYYY
            $table->date('request_date'); // Tgl : dd/mm/yyyy
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->string('area_location')->nullable(); // Area / Lokasi Temuan
            $table->json('source_of_nonconformity')->nullable(); // Keluhan, Audit, dll
            $table->text('nonconformity_description'); // Ketidaksesuaian ditemukan
            $table->text('requirement_violated')->nullable(); // Persyaratan yang dilanggar
            $table->enum('category', ['Temuan', 'Observasi'])->nullable(); // Kategori
            $table->date('due_date')->nullable(); // Batas waktu jawab
            $table->string('illustration_photo_path')->nullable(); // Upload foto/ilustrasi
            $table->enum('status', [
                'waiting_itm_initial_review',
                'waiting_executor',
                'waiting_requester_review',
                'waiting_itm_final_review',
                'completed',
                'rejected'
            ])->default('waiting_itm_initial_review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
