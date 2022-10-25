<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDojosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dojos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('email');
            $table->string('phone');
            $table->bigInteger('organization_id');
            $table->bigInteger('sub_organization_id')->nullable();
            $table->string('country');
            $table->string('dojo_id');
            $table->softDeletes();
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
        Schema::dropIfExists('dojos');
    }
}
