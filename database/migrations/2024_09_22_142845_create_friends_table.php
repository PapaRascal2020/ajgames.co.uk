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
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('friend_id');
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('players')->onDelete('cascade');
        });

        Schema::table('players', function (Blueprint $table) {
            $table->boolean('online_status')->default(false);
            $table->string('photon_room')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
