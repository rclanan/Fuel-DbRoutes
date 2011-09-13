#DbRoutes

Store all of the application routes in a database table.

###Configuration

Create the dbroutes database table

    CREATE TABLE IF NOT EXISTS `dbroutes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `route` text NOT NULL,
        `translation` text NOT NULL,
        PRIMARY KEY (`id`)
    );

###Installation

1.  Place `classes/dbroutes.php` in `fuel/app/classes/`.
2.  Replace `fuel/app/config/routes.php` with the one from this repository in `config/routes.php`.

Thats it!

###Administration

In an admin form allow for three inputs:

* `$url_route` Suggested field input type textarea
* `$named_route` to allow for the support of named routes
* `$translation` the actual real route. Suggested field input type textarea

Basic example processing below:

```php
<?php

// The data below would come from form input
$url_route = 'logout';
$named_route = 'logout';
$translation = 'user/user/logout';

// Process the data and allow for named routes
if ( ! empty($named_route))
{
    $route = array('name' => $named_route, $translation);
}
else
{
    $route = $translation;
}

$data = array(
    'route' => $url_route,
    'translation' => serialize($route)
);


// Put your code to insert the route into the database or update
// it here


// Then recache the routes
DbRoutes::refresh('dbroutes');

```