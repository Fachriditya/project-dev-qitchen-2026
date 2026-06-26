<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->id('category_id');     
            
            $table->string('category_name', 50);       
            $table->string('category_slug', 50)->unique();
            
            $table->timestamps();            
            $table->softDeletes('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};