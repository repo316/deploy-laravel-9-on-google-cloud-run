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

        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->integer('key_data')->index('idx_key_data');
            $table->string('name',180);
            $table->string('zone_type',150)->index('idx_zone_type');
            $table->integer('fk_id_settlement_type');
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
        Schema::dropIfExists('settlements');
    }
};
