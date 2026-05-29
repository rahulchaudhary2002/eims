<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable');
            $table->string('reaction');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['post_id', 'reaction']); // morphs() already creates the reactable composite index
            $table->unique(['post_id', 'reactable_type', 'reactable_id', 'reaction'], 'post_reactions_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reactions');
    }
};
