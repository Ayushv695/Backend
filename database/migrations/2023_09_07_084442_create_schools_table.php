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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('schoolName')->nullable();
            $table->integer('schoolCode')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phoneNo')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->string('affiliatedTo')->nullable();
            $table->integer('establishedYear')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
