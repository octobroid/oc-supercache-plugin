<?php namespace Octobro\SuperCache\Models;

use Model;

/**
 * Model
 */
class CacheRoute extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Validation
     */
    public $rules = [
        'route_pattern' => 'required|unique:octobro_supercache_routes',
        'cache_ttl'     => 'integer',
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octobro_supercache_routes';
}
