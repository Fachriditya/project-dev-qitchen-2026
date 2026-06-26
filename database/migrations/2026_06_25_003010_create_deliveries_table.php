<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {

            $table->id('delivery_id');
            
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('courier_id')->nullable();
            
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 20);
            $table->text('delivery_address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('delivery_status', [
                'waiting',
                'assigned',
                'on_delivery',
                'delivered'
            ])->default('waiting');
            $table->decimal('courier_latitude', 10, 8)->nullable();
            $table->decimal('courier_longitude', 11, 8)->nullable();
            $table->timestamp('delivery_date')->nullable();

            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade'); 
                  
            $table->foreign('courier_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
