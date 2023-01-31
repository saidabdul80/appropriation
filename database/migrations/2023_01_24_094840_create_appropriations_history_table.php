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
        Schema::create('appropriations_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');            
            $table->enum('owner_type',['scheme','department']);
            $table->float('amount',20,2);
            $table->json("appropriation");//[{name:'',percentage_dividend:'',amount:''}]
            $table->enum("source",['BHCPF Funding (FG)','State Counterpart Funding','Premium Sales','Formal Sector Premium','TiSHIP Premium','Other Source']);
            $table->string("description")->nullable();   
            $table->json('fund_category');     
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
        Schema::dropIfExists('appropriations');
    }
};
