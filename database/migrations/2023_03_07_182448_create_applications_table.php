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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->timestamps();
            $table->foreignUuid('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('advert_id')->constrained()->cascadeOnDelete();
            $table->date('attachment_start_date'); // starting date of the attachment period
            $table->smallInteger('minimum_attachment_weeks'); // minimum number of weeks the applicant is supposed to be attached
            $table->date('attachment_end_date'); //// end date of the attachment period
            $table->string('department_approval_status')->default('pending'); //pending, approved, rejected
            $table->string('central_services_approval_status')->default('pending'); //pending, accepted, rejected or canceled
            $table->timestamp('date_replied')->nullable();
            $table->unsignedTinyInteger('offer_accepted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};