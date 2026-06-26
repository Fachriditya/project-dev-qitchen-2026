<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id('order_item_id');
            
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('menu_id');
            
            $table->integer('quantity');
            $table->decimal('item_price', 12, 2);
            $table->decimal('item_subtotal', 12, 2);
            $table->text('item_notes')->nullable();

            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade'); 
                  
            $table->foreign('menu_id')
                  ->references('menu_id')
                  ->on('menus')
                  ->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
