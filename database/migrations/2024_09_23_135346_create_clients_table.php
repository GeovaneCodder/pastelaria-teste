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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            /**
             * In Brazil the phone number has 11 character's
             * When number is not mobile has 10 character's
             */
            $table->string('phone_number', 11);
            $table->date('birthday');
            $table->string('address');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('postal_code');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
