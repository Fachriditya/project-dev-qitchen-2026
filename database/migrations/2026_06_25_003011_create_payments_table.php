<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id('payment_id');
            
            $table->unsignedBigInteger('order_id');
            
            $table->string('midtrans_transaction_id', 100)->unique()->nullable();
            $table->decimal('payment_amount', 12, 2);
            $table->enum('payment_method', [
                'qris',
                'va',
                'cash'
            ]);
            $table->enum('payment_status', [
                'pending',
                'success',
                'failed',
                'expired'
            ]);
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};