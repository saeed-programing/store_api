<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'pending',      #در انتظار تایید
                'confirmed',    #تایید شده - آماده پردازش
                'processing',   #درحال پردازش و آماده سازی برای ارسال
                'shipped',      #ارسال شده
                'delivered',    #تحویل داده شده
                'cancelled',    #لغو شده توسط مشتری
                'unconfirmed',  #تایید نشده توسط فروشنده
                'returned',     #مرجوع شده
            ])->default('pending');
            $table->unsignedBigInteger('total_amount');
            $table->unsignedInteger('delivery_amount')->default(0);
            $table->unsignedBigInteger('payable_amount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
