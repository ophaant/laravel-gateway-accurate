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
        Schema::create('journal_voucher_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('file_name');
            $table->string('file_location')->nullable()->default(null);
            $table->enum('status', ['Hold','Success','Failed'])->default('Hold');
            $table->enum('queue', ['Open','Closed'])->default('Open');
            $table->foreignUuid('bank_id')->constrained('banks')->onDelete('cascade');
            $table->foreignUuid('database_id')->constrained('databases')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_uploads');
    }
};
