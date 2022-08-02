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
        Schema::create('master_settlements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fk_id_master')->index('idx_fk_id_master');
            $table->bigInteger('fk_id_settlement')->index('idx_fk_id_settlement');
            $table->enum('status', ['Active','Inactive'])->default('Active')->index('idx_status');
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
        Schema::dropIfExists('master_settlements');
    }
};
