<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_policies', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('policy_id');

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');

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
        Schema::dropIfExists('roles_policies');
    }
}
