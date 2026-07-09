<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            
            $table->id('voucher_id');

            $table->string('voucher_name', 100);
            $table->string('voucher_code', 50)->unique();
            $table->text('voucher_description')->nullable();
            $table->enum('voucher_type', [
                'percentage',
                'fixed',
                'bogo',
                'free_delivery'
            ]);
            $table->decimal('voucher_value', 12, 2);
            $table->decimal('min_order', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('quota');
            $table->integer('issued_count')->default(0);
            $table->timestamp('expiration_date');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
