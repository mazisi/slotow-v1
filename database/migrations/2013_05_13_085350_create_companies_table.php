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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reg_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('company_type');
            $table->string('business_address')->nullable();
            $table->string('business_address2')->nullable();
            $table->string('business_address3')->nullable();
            $table->string('business_province')->nullable();
            $table->string('business_address_postal_code')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('postal_province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('postal_code2')->nullable();
            $table->string('postal_code3')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('tel_number')->nullable();
            $table->string('tel_number1')->nullable();
            $table->string('active')->nullable();
            $table->string('slug')->nullable();
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
