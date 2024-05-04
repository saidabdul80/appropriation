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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->integer('subhead_id')->nullable()->comment('subhead budget id');
            $table->integer('subhead_item_id')->nullable()->comment('subhead_budget_items id');
            $table->enum('owner_type',['scheme','appropriation']);
            $table->enum('action',['credit','debit']);
            $table->float('amount',15,2);
            $table->unsignedBigInteger('appropriation_history_id')->nullable();
            $table->json('data')->nullable();/* {
                subject:
                Section of Work Plan,
                File Name,
                File Number,
                Page Number,
                Beneficiary,
                taxes:[charges,VAT,Withholding Tax,Stamp Duty]
            }  */
            $table->enum("account_type",['balance','transits','reserve'])->default('balance');
            $table->unsignedBigInteger("source_id")->nullable();
            $table->string("description",5000)->nullable();
            $table->string("fund_category")->nullable();
            $table->unsignedBigInteger("performed_by")->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
