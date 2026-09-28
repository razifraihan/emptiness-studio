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
        Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('concept')->nullable();
    $table->longText('narrative')->nullable();
    $table->string('cover_image')->nullable();
    $table->string('status')->default('draft'); // draft, upcoming, open, closed, archived
    $table->timestamp('opens_at')->nullable();
    $table->timestamp('closes_at')->nullable();
    $table->boolean('is_preorder')->default(false);
    $table->text('preorder_note')->nullable();
    $table->boolean('show_countdown')->default(false);
    $table->unsignedSmallInteger('purchase_limit')->nullable();
    $table->timestamps();
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
