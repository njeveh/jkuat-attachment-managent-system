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
        Schema::create('adverts', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->timestamps();
            $table->foreignUuid('department_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('study_area_id')->constrained()->cascadeOnDelete();                
            $table->string('reference_number')->default('JKUAT');
            $table->text('description')->fulltext('description');
            $table->string('year');
            $table->smallInteger('quarter1_vacancies');
            $table->smallInteger('quarter2_vacancies');
            $table->smallInteger('quarter3_vacancies');
            $table->smallInteger('quarter4_vacancies');
            $table->string('author');
            $table->string('last_updated_by')->nullable();
            $table->string('last_approval_action_done_by')->nullable();
            $table->string('last_activation_action_done_by')->nullable();
            $table->string('approval_status')->default('pending approval'); // pending_approval || approved || disapproved
            $table->unsignedTinyInteger('is_active')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adverts');
    }
};