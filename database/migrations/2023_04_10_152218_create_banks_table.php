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
        Schema::create('banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('account_id');
            $table->string('account_name',50);
            $table->bigInteger('account_number');
            $table->foreignUuid('account_type_id')->constrained('account_bank_types')->onDelete('cascade');
            $table->foreignUuid('category_bank_id')->constrainted('category_banks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
