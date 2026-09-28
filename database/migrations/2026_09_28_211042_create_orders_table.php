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
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();                // ES-20260929-0001
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('customer_name');
    $table->string('customer_email');
    $table->string('customer_phone');
    $table->text('address');
    $table->string('province');
    $table->string('city');
    $table->string('district');
    $table->string('postal_code', 10);
    $table->string('courier')->nullable();
    $table->string('courier_service')->nullable();
    $table->unsignedInteger('subtotal');
    $table->unsignedInteger('shipping_cost')->default(0);
    $table->unsignedInteger('discount')->default(0);
    $table->unsignedInteger('total');
    $table->string('status')->default('pending');         // pending, paid, packed, shipped, done, cancelled
    $table->string('payment_status')->default('unpaid');  // unpaid, paid, expired, refunded
    $table->string('tracking_number')->nullable();
    $table->text('note')->nullable();
    $table->text('gift_note')->nullable();
    $table->text('internal_note')->nullable();
    $table->dateTime('paid_at')->nullable();
    $table->dateTime('expires_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
