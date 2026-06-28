<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {

            $table->id('article_id');
            
            $table->unsignedBigInteger('admin_id');
            
            $table->string('article_title', 150);
            $table->string('article_slug', 150)->unique();
            $table->longText('article_content');
            $table->enum('article_category', [
                'News',
                'Tips',
                'Promo'
            ])->default('News');
            $table->string('thumbnail', 255)->nullable(); 
            $table->boolean('is_published')->default(false);       
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('admin_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
