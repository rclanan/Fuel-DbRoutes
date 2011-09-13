<?php
return array_merge(array(
	'_root_'        => 'welcome/index',  // The default route
	'_404_'         => 'welcome/404',    // The main 404 route
), DbRoutes::load('dbroutes'));
