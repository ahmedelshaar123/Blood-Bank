<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('email')->unique();
			$table->date('date_of_birth');
			$table->integer('blood_type_id')->unsigned();
			$table->date('last_date_of_donation');
			$table->integer('city_id')->unsigned();
			$table->integer('phone')->unique();
			$table->string('password');
			$table->boolean('is_active')->default(1);
			$table->smallInteger('pin_code')->nullable();
			$table->string('api_token', 60)->unique()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}