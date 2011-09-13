<?php

/**
 * Handles all the loading, caching and re-caching of routes from a
 * database table.
 */
class DbRoutes {
	
	/**
	 * Loads in the routes from the database or cache, and caches them if
	 * it is not already cached.
	 *
	 * @param   string  $table  routes table name
	 * @return  array   routes array
	 */
	public static function load($table = 'dbroutes')
	{
		try
		{
			$routes = Cache::get('routes');
		}
		catch(CacheNotFoundException $e)
		{
			$routes = array();

			// Note: The real_route is serialized to support named routes
			$db_routes = DB::select('*')->from($table)->execute()->as_array();
			if ($db_routes)
			{
				foreach ($db_routes as $dbr)
				{
					$routes[$dbr['route']] = unserialize($dbr['translation']);
				}
			}
			Cache::set('routes', $routes);
		}
		return $routes;
	}

	/**
	 * Refreshes the routes cache.
	 *
	 * @param   string  $table  routes table name
	 * @return  array   new cached routes array
	 */
	public static function refresh($table = 'dbroutes')
	{
		Cache::delete('routes');
		return DbRoutes::load('dbroutes');
	}
}