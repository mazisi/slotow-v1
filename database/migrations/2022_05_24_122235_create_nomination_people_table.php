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
        Schema::create('nomination_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomination_id')->constrained()->onDelete('cascade');
            $table->foreignId('people_id')->constrained()->onDelete('cascade');
            $table->string('relationship')->nullable();
            $table->timestamp('terminated_at')->nullable();
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
        Schema::dropIfExists('nomination_people');
    }
};
