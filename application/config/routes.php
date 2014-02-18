<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// These two MUST come first
// Default route when no URI is specified
$route['default_controller'] = "pages";
// 404 controller class, default=application/errors/error_404.php
$route['404_override'] = '';

/**
 * CodeIgniter mapping defaults to URL format controller/method/param1/param2.
 * Therefore, if your method arguments are in sequential order (e.g. $1/$2/$3)
 * we don't need to add anything here to make it work. Magic!
 *
 * Only define routes for paths where the arguments are out of order or the URL path doesn't match the controller/method names.
 */
$route['home'] = "pages";
$route['narratives/(:num)'] = "player/index/$1";
$route['admin/narratives/(:num)/(:any)'] = "admin_narrative/$2/$1";
$route['admin/narratives/(:num)'] = "admin_narrative/index/$1";
$route['admin/(:any)'] = "admin/$1";




/* End of file routes.php */
/* Location: ./application/config/routes.php */
