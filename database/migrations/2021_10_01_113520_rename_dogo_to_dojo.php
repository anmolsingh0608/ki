<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameDogoToDojo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_cards', function (Blueprint $table) {
            DB::statement('ALTER TABLE membership_cards CHANGE dogo_id dojo_id bigint(20)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_cards', function (Blueprint $table) {
            DB::statement('ALTER TABLE membership_cards CHANGE dojo_id dogo_id bigint(20)');
        });
    }
}
