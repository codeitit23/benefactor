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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donation_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('donation_type', ['cash', 'item'])->default('item');
            $table->foreignId('item_type_id')->nullable()->constrained('item_types')->onDelete('set null');
            $table->foreignId('item_status_id')->nullable()->constrained('item_statuses')->onDelete('set null');

            // Cash donation fields
            $table->enum('payment_method', ['cash', 'wish', 'omt', 'credit_card'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();

            // Item donation fields
            $table->json('item_images')->nullable(); // Store up to 5 image paths
            $table->string('item_video')->nullable(); // Single video path
            $table->date('pickup_date')->nullable();
            $table->text('notes')->nullable();

            // Admin fields
            $table->string('current_status')->default('pending'); // pending, approved, rejected, completed
            $table->text('status_note')->nullable();
            $table->json('beneficiary_images')->nullable(); // Admin uploaded images
            $table->string('beneficiary_video')->nullable(); // Admin uploaded video

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
