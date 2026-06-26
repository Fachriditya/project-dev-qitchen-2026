<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {

            $table->id('referral_id');
            
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('referred_user_id');
            $table->unsignedBigInteger('voucher_id')->nullable();
            
            $table->timestamp('rewarded_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->foreign('referrer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade'); 
                  
            $table->foreign('referred_user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade'); 
                  
            $table->foreign('voucher_id')
                  ->references('voucher_id')
                  ->on('vouchers')
                  ->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
