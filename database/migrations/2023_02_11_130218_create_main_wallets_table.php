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
        Schema::create('main_wallets', function (Blueprint $table) {
            $table->id();
            $table->enum("owner_type",['appropriation','scheme']);
            $table->unsignedBigInteger("owner_id");
            $table->float("balance",15,2)->default(0.00);
            $table->float("total_collection",15,2)->default(0.00);  
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
        Schema::dropIfExists('main_wallets');
    }
};
