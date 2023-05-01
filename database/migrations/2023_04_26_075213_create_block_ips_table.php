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
        Schema::create('block_ips', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->string('description',100)->nullable();
            $table->enum('type',['Production','Development'])->default('Production');
            $table->enum('status',['Enable','Disable','Progress'])->default('Disable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_ips');
    }
};
