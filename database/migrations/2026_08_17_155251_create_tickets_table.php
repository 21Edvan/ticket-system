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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();

        $table->string('ticket_number')->unique();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('category_id')
            ->constrained()
            ->restrictOnDelete();

        $table->foreignId('assigned_to')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->string('title', 150);
        $table->text('description');

        $table->string('priority')->default('medium');
        $table->string('status')->default('open');

        $table->timestamp('resolved_at')->nullable();
        $table->timestamp('closed_at')->nullable();

        $table->timestamps();

        $table->index('status');
        $table->index('priority');
        $table->index('assigned_to');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
