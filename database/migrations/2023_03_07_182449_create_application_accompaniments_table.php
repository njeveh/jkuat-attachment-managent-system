<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_accompaniments', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->timestamps();
            $table->string('name');
            $table->foreignUuid('application_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('status')->default('pending_review'); //pending_review, accepted, rejected
            $table->string('review_remarks')->default('Pending review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_accompaniments');
    }
};