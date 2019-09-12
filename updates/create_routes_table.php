<?php namespace Octobro\Cacheroute\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRoutesTable extends Migration
{
    public function up()
    {
        Schema::create('octobro_supercache_routes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('route_pattern', 255);
            $table->integer('cache_ttl');
            $table->integer('sort_order')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('octobro_supercache_routes');
    }
}
