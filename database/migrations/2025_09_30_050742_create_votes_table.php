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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('vote_value', ['yes', 'no', 'for', 'against', 'abstain'])->notNull();
            $table->bigInteger('votes_cast')->notNull();
            $table->timestamp('voted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('agenda_id')
                ->references('id')
                ->on('agendas')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Ensure votes are unique on an agenda item per user
            $table->unique(['agenda_id', 'user_id'], 'unique_vote_on_agenda_item_per_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
