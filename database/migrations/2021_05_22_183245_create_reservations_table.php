<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('departure_place', 100);
            $table->string('departure_meeting_point', 100);
            $table->date('departure_date');
            $table->time('departure_hour', 0);
            $table->string('departure_ticket_number', 100)->nullable();
            $table->string('arrival_place', 100);
            $table->string('arrival_meeting_point', 100);
            $table->date('arrival_date');
            $table->time('arrival_hour', 0);
            $table->string('arrival_ticket_number', 20)->nullable();
            $table->float('total', 8, 2)->default(0);
            $table->float('services_total', 8, 2)->default(0);
            $table->foreignId('client_id')->constrained();
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
        Schema::dropIfExists('reservations');
    }
}
