## \*_Skenario _

1. **Semua user** bisa **input request** ke **user lainnya** (pilih dari daftar: MPS, QQ, CR, Distr., SSGA, HSSE, dst.).
2. **Hanya ITM** yang **review & approve** request **sebelum** dikirim ke user tujuan.
3. **Setelah approved oleh ITM**:

    - Request masuk ke **user tujuan** (misal QQ).
    - User tujuan (QQ) akan **mengisi rincian perbaikan** + upload dokumen foto.

4. **Setelah QQ submit rincian perbaikan**:

    - **User yang request awal** (MPS) **review** rincian perbaikan itu.
    - Jika setuju, dia "approve".

5. **Setelah MPS approve rincian**:

    - **Masuk ke ITM lagi** untuk **final approve**.

6. **Setelah ITM final approve**:
    - Dokumen PDF dihasilkan, lengkap dengan tanda tangan QR semua pihak yang approve.

---

## **Alur Status Secara Bertahap**

| Status                     | Penjelasan                                                        |
| :------------------------- | :---------------------------------------------------------------- |
| `waiting_itm_review`       | Baru diinput, menunggu ITM approve                                |
| `waiting_executor`         | Sudah approve ITM, menunggu QQ (atau user tujuan) mengisi rincian |
| `waiting_requester_review` | QQ sudah submit rincian, nunggu yang request awal (MPS) approve   |
| `waiting_itm_final`        | Setelah MPS approve rincian, nunggu ITM final approve             |
| `completed`                | Selesai semua dan PDF keluar                                      |
| `rejected`                 | Ditolak pada salah satu tahap                                     |

---

## **Struktur Utama Database (Diperbarui)**

| Table             | Fields                                                                                                                          |
| :---------------- | :------------------------------------------------------------------------------------------------------------------------------ |
| `users`           | id, name, email, password, role                                                                                                 |
| `requests`        | id, title, description, from_user_id, to_user_id, status, created_at, updated_at                                                |
| `request_details` | id, request_id, resolver_user_id, description, photo_path, submitted_at                                                         |
| `approvals`       | id, request_id, approver_user_id, stage (itm_review, executor_response, requester_review, itm_final), qr_code_path, approved_at |

```php
Schema::create('users', function (Blueprint $table) {
   $table->id();
   $table->string('name');
   $table->string('email')->unique();
   $table->timestamp('email_verified_at')->nullable();
   $table->string('password');
   $table->enum('role', ['MPS', 'QQ', 'SSGA', 'LM', 'Distr', 'CR', 'HSSE', 'ITM']);
   $table->rememberToken();
   $table->timestamps();
});

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

Schema::create('approvals', function (Blueprint $table) {
   $table->id();
   $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
   $table->foreignId('approver_user_id')->constrained('users')->onDelete('cascade');
   $table->enum('stage', [
         'itm_initial_review', // pertama
         'executor_response', // executor input
         'requester_review', // requester review
         'itm_final_review' // final approve
   ]);
   $table->timestamp('approved_at')->nullable();
   $table->string('qr_code_path')->nullable(); // path file QR signature
   $table->enum('verification_status', ['Close', 'Follow Up'])->nullable();
   $table->date('next_verification_target')->nullable(); // Jika follow up
   $table->timestamps();
});
```
