<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id('order_id');
            
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->string('order_number', 20)->unique();        
            $table->enum('order_type', [
                'dine_in', 
                'takeaway', 
                'delivery'
            ]);
            $table->enum('order_status', [
                'pending', 
                'paid', 
                'cooking', 
                'ready', 
                'completed', 
                'cancelled'
            ]);
            $table->decimal('order_subtotal', 12, 2);
            $table->decimal('order_discount', 12, 2);
            $table->decimal('order_total', 12, 2);

            $table->unsignedBigInteger('voucher_id')->nullable();
            
            $table->string('order_notes', 255)->nullable();
            
            $table->timestamps();
            $table->softDeletes('deleted_at'); 

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('voucher_id')
                  ->references('voucher_id')
                  ->on('vouchers')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
