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
        Schema::create('applicants', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->timestamps();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('national_id')->unique();
            $table->string('first_name');
            $table->string('second_name');
            $table->string('phone_number');
            $table->string('institution');
            /**
             * 0=>has_made_no_application, 1=>has_made_application, 2=>got_departmental_approval_or_rejection, 3=>got_response,
             * 4=>got_offer_but_offer_revoked 5=>got_and_accepted_offer, 6=>reported, 7=>terminated_before_completion, 8=>'completed'.
             */
            $table->smallInteger('engagement_level')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};