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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('facility_id')->unsigned();
            $table->timestamp('access_time');
            $table->timestamp('check_out_time')->nullable();
            $table->timestamps();
        
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('facility_id')->references('id')->on('facilities');
        });              
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_logs');
    }
};