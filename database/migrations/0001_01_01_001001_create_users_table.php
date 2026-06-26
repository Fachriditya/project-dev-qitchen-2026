<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id('user_id');          

            $table->string('name', 100);      
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();      
            $table->string('password', 255)->nullable();  
            $table->string('phone', 20)->nullable(); 
            $table->string('google_id', 50)->nullable();   
            $table->string('otp', 6)->nullable();   
            $table->enum('role', [
                'admin',
                'cashier',
                'kitchen',
                'courier',
                'customer'
            ]);             
            $table->string('referral_code', 15)->unique()->nullable();     
            $table->unsignedBigInteger('referred_by')->nullable(); 

            $table->rememberToken();

            $table->timestamps();    
            $table->softDeletes('deleted_at');

            $table->foreign('referred_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};