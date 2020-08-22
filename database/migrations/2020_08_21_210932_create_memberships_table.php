<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMembershipsTable.
 */
class CreateMembershipsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('memberships', function(Blueprint $table) {
            $table->increments('id');
			$table->string('member_type')->index()->nullable();
			$table->string('member_id')->index()->nullable();
            $table->integer('group_id')->index()->nullable();
            $table->string('role')->index()->default('member');
			$table->timestamp('approved_at')->index()->nullable();
			$table->timestamp('rejected_at')->index()->nullable();
			$table->timestamp('starts_at')->index()->nullable();
			$table->timestamp('expires_at')->index()->nullable();
			$table->timestamp('removed_at')->index()->nullable();
            $table->integer('created_by')->index()->nullable();
            $table->nullableTimestamps();
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
		Schema::drop('memberships');
	}
}
