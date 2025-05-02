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
        Schema::create('mwt_reports', function (Blueprint $table) {
            $table->id();
            $table->date('execution_date');
            $table->json('dialogues')->nullable();
            $table->json('positive_findings')->nullable();
            $table->json('unsafe_conditions')->nullable();
            $table->string('documentation_path')->nullable();
            $table->foreignId('acknowledged_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->json('acknowledged_by_qr_code_content')->nullable();
            $table->json('approved_by_qr_code_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mwt_reports');
    }
};
