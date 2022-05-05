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
        Schema::create('brodcastdates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('weekday');
            $table->integer('delay');
            $table->string('time');
            $table->string('link');
            $table->integer('last_broadcast')->default(0);
            $table->boolean('NEXT')->default(false);
            $table->boolean("ACTIVE")->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brodcastdates');
    }
};
