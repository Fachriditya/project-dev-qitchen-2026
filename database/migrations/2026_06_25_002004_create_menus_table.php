<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {

            $table->id('menu_id');
            
            $table->unsignedBigInteger('category_id');
            
            $table->string('menu_name', 100);
            $table->string('menu_slug', 100)->unique();
            $table->text('menu_description')->nullable();
            $table->decimal('menu_price', 12, 2);
            $table->string('menu_image', 255)->nullable();
            $table->boolean('is_available')->default(true); 
            
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('restrict'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
