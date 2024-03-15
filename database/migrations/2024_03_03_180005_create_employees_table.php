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
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fname', 255)->nullable();
            $table->string('lname', 255)->nullable();
            $table->string('nickname', 255)->nullable();
            $table->enum('sex', ['M', 'F'])->nullable();
            $table->string('tel', 255)->nullable();
            $table->string('social_type', 255)->nullable();
            $table->string('social_id', 255)->nullable();
            $table->boolean('status')->default(1);
            
             // Foreign Key
            $table->integer('prefix_id')->nullable()->unsigned()->index();
            $table->foreign('prefix_id')->references('id')->on('prefixes')->onDelete('cascade');

            $table->integer('departments_id')->nullable()->unsigned()->index();
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');

            $table->integer('provinces_id')->nullable()->unsigned()->index();
            $table->foreign('provinces_id')->references('id')->on('thai_provinces')->onDelete('cascade');
            
            $table->string('create_by', 100)->nullable();
            $table->string('update_by', 100)->nullable();

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
        Schema::dropIfExists('employees');
    }
};
