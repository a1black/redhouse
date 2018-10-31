<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Creates database tables and set initial values.
 */
class CreateTables extends Migration
{
    public function up()
    {
        // Organization contact list
        Schema::create('redhouse_shelter_contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Schema::create('redhouse_shelter_contact_numbers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contact_id');
            $table->string('number');
            $table->char('type', 5);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('contact_id')
                ->references('id')
                ->on('redhouse_shelter_contacts')
                ->onDelete('cascade');
        });

        // Organization bank accounts and e-wallets
        Schema::create('redhouse_shelter_cash_accounts', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type');
            $table->string('bank_name'); // Имя банка
            $table->string('account'); // Р.сч
            $table->string('bank_id_code')->nullable(); // БИК
            $table->string('correspondent')->nullable(); // К.сч
            $table->string('transfer_url')->nullable(); // URL для проведения платежа
            $table->text('embedded_html')->nullable(); // HTML для размещения на сайте
            $table->timestamps();
        });

        // Animal catalog
        Schema::create('redhouse_shelter_animals', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug');
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('sex');
            $table->date('birthday');
            $table->string('health');
            $table->text('health_info')->nullable();
            $table->string('fundraise_url')->nullable();
            $table->text('description');
            $table->boolean('adopted');
            $table->date('adopted_at')->nullable();
            $table->string('adopted_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('redhouse_shelter_animals');
        Schema::dropIfExists('redhouse_shelter_contacts');
        Schema::dropIfExists('redhouse_shelter_contact_numbers');
        Schema::dropIfExists('redhouse_shelter_cash_accounts');

        Schema::enableForeignKeyConstraints();
    }
}
