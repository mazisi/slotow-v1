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
        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->year('year')->nullable();
            $table->string('document')->nullable();
            $table->foreignId('licence_id')->constrained()->onDelete('cascade');
            $table->string('status')->nullable();
            $table->date('client_paid_date')->nullable();
            $table->date('nomination_lodged_at')->nullable();
            $table->date('nomination_issued_at')->nullable();
            $table->date('nomination_delivered_at')->nullable();
            $table->date('payment_to_liquor_board_at')->nullable();
            $table->string('slug');
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
        Schema::table('nominations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
