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
        Schema::create('influencers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->enum('sex', ['M', 'F'])->nullable();
            $table->string('tel', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('line', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->text('current_address')->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_no', 255)->nullable();
            $table->string('citizen_name', 255)->nullable();
            $table->string('citizen_no', 255)->nullable();
            $table->text('citizen_address')->nullable();
            $table->text('copy_card')->nullable();
            $table->text('copy_back')->nullable();
            $table->string('ex_work', 255)->nullable();

            $table->string('link_tiktok', 255)->nullable();
            $table->string('name_tiktok', 255)->nullable();
            $table->integer('fol_tiktok')->nullable();

            $table->string('link_facebook', 255)->nullable();
            $table->string('name_facebook', 255)->nullable();
            $table->integer('fol_facebook')->nullable();

            $table->string('link_ig', 255)->nullable();
            $table->string('name_ig', 255)->nullable();
            $table->integer('fol_ig')->nullable();

            $table->string('link_youtube', 255)->nullable();
            $table->string('name_youtube', 255)->nullable();
            $table->integer('fol_youtube')->nullable();

            $table->string('link_twitter', 255)->nullable();
            $table->string('name_twitter', 255)->nullable();
            $table->integer('fol_twitter')->nullable();

            $table->string('link_blog', 255)->nullable();
            $table->string('name_blog', 255)->nullable();
            $table->integer('fol_blog')->nullable();

            $table->string('social_type', 255)->nullable();
            $table->string('social_id', 255)->nullable();
            
            $table->boolean('status')->default(true);
            // Foreign Key.
            $table->integer('provinces_id')->nullable()->unsigned()->index();
            $table->foreign('provinces_id')->references('id')->on('thai_provinces')->onDelete('cascade');

            $table->integer('bank_id')->nullable()->unsigned()->index();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->enum('report', ['G', 'F'])->nullable();
            $table->string('report_reasons', 255)->nullable();
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
        Schema::dropIfExists('influencers');
    }
};
