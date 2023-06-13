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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            //we use the foreign id to create a field where the id of the user creating the follow is stored
            $table->foreignId('user_id')->constrained();//the constrained method allows only real users create a follow
            //we also need to store the id of the user being followed 
            $table->unsignedBigInteger('followeduser'); 
            $table->foreign('followeduser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
