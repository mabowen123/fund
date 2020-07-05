<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_funds', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('user_id')->default(0)->index('user_id');
            $table->char('fund_id', 6)->default('');
            $table->unsignedDecimal('share', 10, 2)->default('0')->comment('份额');
            $table->unsignedDecimal('amount', 10, 2)->default('0')->comment('投入成本');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_funds');
    }
}
