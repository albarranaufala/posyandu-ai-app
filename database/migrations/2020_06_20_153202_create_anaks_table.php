<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anaks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_unik')->unique();
            $table->string('nama_anak', 255);
            $table->string('nama_ibu', 255);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('jenis_kelamin', 255);
            $table->string('no_telepon', 13);
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
        Schema::dropIfExists('anaks');
    }
}
