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
        Schema::create('agms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');

            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamp('meeting_date');
            $table->timestamp('voting_start_time');
            $table->timestamp('voting_end_time');
            $table->enum('status', ['draft', 'active', 'closed', 'cancelled'])->default('draft');
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            // Composite unique key
            $table->unique(['company_id', 'title'], 'unique_title_per_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agms');
    }
};
