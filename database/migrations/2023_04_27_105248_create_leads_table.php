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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('gi');
            $table->string('token');
            $table->string('aff_param1')->nullable(true)->default(null);   // source name
            $table->string('aff_param2')->nullable(true)->default(null);   // campaign name
            $table->string('aff_param3')->nullable(true)->default(null);   // description
            $table->string('aff_param4')->nullable(true)->default(null);   // FreeParam
            $table->string('aff_param5')->nullable(true)->default(null);   // FreeParam
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
