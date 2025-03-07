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
        Schema::create('attachees', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->timestamps();
            $table->foreignUuid('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('application_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('department_id')->constrained()->cascadeOnDelete();
            $table->string('year')->nullable(); //the finacial/academic year they are attached
            $table->unsignedTinyInteger('quarter')->nullable(); //1,2,3,4
            /**
             * active, terminated_before_completion, completed,
             */
            $table->string('status')->default('active');
            $table->string('study_area')->nullable();
            $table->timestamp('date_started')->nullable();
            $table->timestamp('date_terminated')->nullable();
            $table->string('termination_reason')->nullable();
            $table->tinyInteger('has_filled_evaluation_form')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachees');
    }
};