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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agm_id');
            $table->integer('item_number');
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->enum('item_type', ['resolution', 'election', 'approval', 'other'])->default('resolution');
            $table->enum('voting_type', ['yes_no', 'for_against_abstain', 'multiple_choice'])->default('yes_no');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('agm_id')
                ->references('id')
                ->on('agms')
                ->onDelete('cascade');

            // Ensure agenda items are unique per AGM
            $table->unique(['agm_id', 'title'], 'unique_item_title_per_agm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
