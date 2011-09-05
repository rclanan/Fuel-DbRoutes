#DbRoutes

    Store all of the application routes in a database table.

###Configuration

    Create the dbroutes database table

    CREATE TABLE IF NOT EXISTS `dbroutes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `route` text NOT NULL,
    `real_route` text NOT NULL,
    PRIMARY KEY (`id`)
    );

###Installation

    Replace app/config/routes.php with the following

    <?php
    try
    {
        $routes = Cache::get('routes');
    }
    catch(CacheNotFoundException $e)
    {
        $routes = array(
            '_root_'        => 'welcome/index',  // The default route
            '_404_'         => 'welcome/404',    // The main 404 route
        );

        // Note: The real_route is serialized to support named routes
        $db_routes = DB::select('*')->from('dbroutes')->execute()->as_array();

        foreach ($db_routes as $dbr)
        {
            $dbroutes[$dbr['route']] = unserialize($dbr['real_route']);
        }

        $routes = array_merge((array)$routes, (array)$dbroutes);

        Cache::set('routes', $routes);
    }
    return $routes;

###Administration

    In an admin form allow for three inputs:

    *$url_route Suggested field input type textarea

    *$named_route to allow for the support of named routes

    *$real_route the actual real route. Suggested field input type textarea

    Basic example processing below:

    *** The data below would come from form input
    $url_route = 'logout';
    $named_route = 'logout';
    $real_route = 'user/user/logout';

    // Process the data and allow for named routes
    if ( ! empty($named_route))
    {
        $route = array('name' => $named_route, $real_route);
    }
    else
    {
        $route = $real_route;
    }

    $data = array(
        'route' => $url_route,
        'real_route' => serialize($route)
    );

    // Then delete the cache
    Cache::delete('routes');

    // Then save the new route to the database table