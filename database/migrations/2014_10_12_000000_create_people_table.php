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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('active')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_or_passport')->nullable();
            $table->string('passport')->nullable();
            $table->string('email_address_1')->nullable();
            $table->string('email_address_2')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('telephone')->nullable();
            $table->date('passport_valid_until')->nullable();
           
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
        Schema::table('people', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
