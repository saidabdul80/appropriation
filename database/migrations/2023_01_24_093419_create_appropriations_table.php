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
        Schema::create('appropriations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("scheme_id");
            $table->longText("department_id");
            $table->string("name");
            $table->unsignedBigInteger("wallet_number")->index()->nullable();
            $table->float("percentage_dividend");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shareholders');
    }
};
