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
        Schema::create('subhead_budget_items', function (Blueprint $table) {
            $table->id();
            $table->integer('item_name_id')->comment('subhead_budget_item_names table id ');
            $table->integer('subhead_budget_id');
            $table->string('output',500);
            $table->string('outcome',500);
            $table->float('amount',15)->default(0.00);
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
        Schema::dropIfExists('subhead_budget_items');
    }
};
