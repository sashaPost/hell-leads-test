<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('password');
            $table->string('firstname', 60);
            $table->string('lastname', 60);
            $table->string('country');
            $table->string('phone')->unique();
            $table->string('ip');
            $table->string('sub_id1')->nullable(true)->default(null);
            $table->string('sub_id2')->nullable(true)->default(null);
            $table->string('sub_id3')->nullable(true)->default(null);
            $table->string('sub_id4')->nullable(true)->default(null);
            $table->string('sub_id5')->nullable(true)->default(null);
            $table->unsignedBigInteger('lead_id')->nullable(true)->default(null);
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
