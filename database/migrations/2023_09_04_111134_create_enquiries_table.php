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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->integer('clientID')->nullable();
            $table->enum('step',['Lead', 'HotLead', 'Client'])->nullable()->default('Lead');
            $table->string('email')->unique()->nullable();
            $table->string('name')->nullable();
            $table->text('message')->nullable();
            $table->date('enquiryDate')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status',['Active','Inactive'])->nullable()->default('Active');
            $table->string('course')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
