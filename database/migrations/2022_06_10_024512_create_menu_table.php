<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            'id' => '8',
            'name' => 'Anak',
            'type' => 'menu',
            'parent' => '6',
            'order' => '1',
            'icon' => 'child',
            'link' => '/childrens-room',
            'target' => '_blank',
        ];
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['label', 'menu'])->default('menu');
            $table->integer('parent');
            $table->integer('order');
            $table->string('icon');
            $table->string('link');
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->softDeletes('deleted_at', 0);
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
        Schema::dropIfExists('menu');
    }
}
