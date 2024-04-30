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
        Schema::create('sub_head_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('subhead_id');
            $table->integer('appropriation_id')->comment('represent the head');
            $table->float('amount')->default(0.00);
            $table->string('fund_category',10);
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
        Schema::dropIfExists('sub_heads_budgets');
    }
};
