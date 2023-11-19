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
        Schema::create('licence_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->integer('old_company_id');
            $table->string('transfered_to')->nullable();
            $table->date('date')->nullable();
            $table->string('status')->nullable();
            $table->date('lodged_at')->nullable();
            $table->date('activation_fee_paid_at')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('payment_to_liquor_board_at')->nullable();
            $table->string('merged_document')->nullable();
            $table->string('slug');
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
        Schema::table('licence_transfers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
