<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->string('email', 50)->unique();
            $table->string('phone', 45)->unique();
            $table->string('alternate_phone', 45)->nullable()->unique();
            $table->string('address');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('entry_by_user')->references('id')->on('users');
            $table->foreignId('salesman_id')->nullable()->constrained('staff');
            $table->string('register_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
