<?php

namespace DbRoutes;
/**
 * Handles all the loading, caching and re-caching of routes from a
 * database table.
 */
class DbRoutes
{

    public static $table = null;
    public static $cache_id = null;

    /**
     * @static
     * @return void
     */
    public static function _init()
    {
        \Config::load('dbroutes', true);

        static::$table = \Config::get('dbroutes.db.table', 'dbroutes');
        static::$cache_id = \Config::get('dbroutes.cache.cacheid', 'routes');
    }

    /**
     * Loads in the routes from the database or cache, and caches them if
     * it is not already cached.
     *
     * @param   string  $table  routes table name
     * @return  array   routes array
     */
    public static function load()
    {
        try
        {
            $routes = \Cache::get(static::$cache_id);
        }
        catch (\CacheNotFoundException $e)
        {
            $routes = array();

            // Note: The real_route is serialized to support named routes
            $db_routes = \DB::select('*')->from(static::$table)->execute()->as_array();
            if ($db_routes) {
                foreach ($db_routes as $dbr)
                {
                    $routes[$dbr['route']] = unserialize($dbr['translation']);
                }
            }
            \Cache::set(static::$cache_id, $routes);
        }
        return $routes;
    }

    /**
     * Refreshes the routes cache.
     *
     * @param   string  $table  routes table name
     * @return  array   new cached routes array
     */
    public static function refresh()
    {
        \Cache::delete(static::$cache_id);
        return DbRoutes::load();
    }
}