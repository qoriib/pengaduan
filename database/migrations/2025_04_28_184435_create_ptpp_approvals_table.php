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
        Schema::create('ptpp_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('ptpp_requests')->onDelete('cascade');
            $table->foreignId('approver_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('stage', [
                'itm_initial_review', // pertama
                'executor_response', // executor input
                'requester_review', // requester review
                'itm_final_review' // final approve
            ]);
            $table->timestamp('approved_at')->nullable();
            $table->json('qr_code_content')->nullable(); // path file QR signature
            $table->enum('verification_status', ['Close', 'Follow Up'])->nullable();
            $table->date('next_verification_target')->nullable(); // Jika follow up
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptpp_approvals');
    }
};
