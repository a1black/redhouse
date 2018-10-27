<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Migration;
use Schema;


/**
 * Creates database tables and set initial values.
 */
class CreateTables extends Migration
{
    public function up()
    {
        // Organization contact list
        Schema::create('redhouse_shelter_contacts', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->boolean('published')->default(true);
        });

        Schema::create('redhouse_shelter_contact_numbers', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contact_id');
            $table->string('number');
            $table->char('type', 5);
            $table->boolean('enabled')->default(true);

            $table->foreign('contact_id')
                ->references('id')
                ->on('redhouse_shelter_contacts')
                ->onDelete('cascade');
        });

        // Organization bank accounts and e-wallets
        //Schema::create('redhouse_shelter_cash_account', function($table) {
        //    $table->engine = 'InnoDB';
        //    $table->increments('id');
        //    $table->string('type', 5);
        //    $table->string('acount')->index();
        //    $table->string('bank_id_code')->nullable();
        //    $table->string('bank_page')->nullable();
        //});
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('redhouse_shelter_contacts');
        Schema::dropIfExists('redhouse_shelter_contact_numbers');
        Schema::dropIfExists('redhouse_shelter_cash_account');

        Schema::enableForeignKeyConstraints();
    }
}
