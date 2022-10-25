<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_cards', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->dateTime('membership_date');
            $table->bigInteger('dogo_id');
            $table->bigInteger('organization_id')->nullable();
            $table->bigInteger('sub_organization_id')->nullable();
            $table->string('program');
            $table->string('member_id');
            $table->string('rank');
            $table->string('card_type');
            $table->bigInteger('order_id')->nullable();
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
        Schema::dropIfExists('membership_cards');
    }
}
