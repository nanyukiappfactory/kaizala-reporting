<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */
$route['default_controller'] = 'admin/admin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

/**
 * Administration
 */
$route['administration'] = 'admin/admin/index';

/**
 * Groups
 */
$route['administration/all-groups'] = 'admin/group/index';
$route['administration/search-groups'] = ['admin/group/search_groups'];
$route['administration/groups-close-search'] = ['admin/group/close_search'];
$route['administration/all-groups/(:any)/(:any)/(:num)'] = 'admin/group/index/$1/$2/$3';
$route['administration/all-groups/(:any)/(:any)'] = 'admin/group/index/$1/$2';
$route['administration/fetch-groups'] = 'admin/group/fetch_groups';
$route['administration/group-users/(:any)/(:num)'] = 'admin/group/get_group_users/$1/$2';
$route['administration/all-users/(:any)/(:any)'] = 'admin/group/group_users/$1/$2';
$route['administration/deactivate-group/(:num)'] = 'admin/group/deactivate_group/$1';
$route['administration/activate-group/(:num)'] = 'admin/group/activate_group/$1';

/**
 * Action Cards
 */
$route['actions/get-actions'] = 'admin/action/index';

$route['administration/all-actions'] = 'admin/actioncard/index';
$route['administration/all-responses/(:num)/(:any)/(:any)/(:num)'] = 'admin/actioncard/get_responses/$1/$2/$3/$4';
$route['administration/all-responses/(:num)/(:any)/(:any)'] = 'admin/actioncard/get_responses/$1/$2/$3';
$route['administration/all-actions/(:any)/(:any)'] = 'admin/actioncard/index/$1/$2';
$route['administration/all-responses/(:num)'] = 'admin/actioncard/get_responses/$1';
$route['administration/edit-package-name/(:num)'] = 'admin/actioncard/edit_package_name/$1';
