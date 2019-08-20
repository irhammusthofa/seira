<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'authentication';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['logout/(:any)'] = 'authentication/logout/$1';
$route['admin/auth/dologin'] = 'admin/authentication/dologin';
$route['admin/auth/login'] = 'admin/authentication/index';
$route['admin/logout'] = 'admin/authentication/logout';
$route['admin/instruktur/kategori/(:any)'] = 'admin/instrukturdetail/index/$1';
$route['admin/instruktur/kategori/add/(:any)'] = 'admin/instrukturdetail/add/$1';
$route['admin/instruktur/kategori/simpan/(:any)'] = 'admin/instrukturdetail/simpan/$1';
$route['admin/instruktur/kategori/simpan/(:any)/(:any)'] = 'admin/instrukturdetail/simpan/$1/$2';
$route['admin/instruktur/kategori/edit/(:any)/(:any)'] = 'admin/instrukturdetail/edit/$1/$2';
$route['admin/instruktur/kategori/hapus/(:any)/(:any)'] = 'admin/instrukturdetail/hapus/$1/$2';

$route['admin/jadwal/instruktur'] = 'admin/jadwalinstruktur/index';
$route['admin/jadwal/instruktur/add'] = 'admin/jadwalinstruktur/add';
$route['admin/jadwal/instruktur/simpan'] = 'admin/jadwalinstruktur/simpan';
$route['admin/jadwal/instruktur/simpan/(:any)'] = 'admin/jadwalinstruktur/simpan/$1';
$route['admin/jadwal/instruktur/hapus/(:any)'] = 'admin/jadwalinstruktur/hapus/$1';
$route['admin/jadwal/instruktur/edit/(:any)'] = 'admin/jadwalinstruktur/edit/$1';

$route['admin/jadwal/anggota'] = 'admin/jadwalanggota/index';
$route['admin/jadwal/anggota/add'] = 'admin/jadwalanggota/add';
$route['admin/jadwal/anggota/simpan'] = 'admin/jadwalanggota/simpan';
$route['admin/jadwal/anggota/simpan/(:any)'] = 'admin/jadwalanggota/simpan/$1';
$route['admin/jadwal/anggota/hapus/(:any)'] = 'admin/jadwalanggota/hapus/$1';
$route['admin/jadwal/anggota/edit/(:any)'] = 'admin/jadwalanggota/edit/$1';

$route['anggota/auth/dologin'] = 'anggota/authentication/dologin';
$route['anggota/auth/login'] = 'anggota/authentication/index';
$route['anggota/signup'] = 'anggota/authentication/signup';
$route['anggota/signup/dosignup'] = 'anggota/authentication/dosignup';


$route['instruktur/auth/dologin'] = 'instruktur/authentication/dologin';
$route['instruktur/auth/login'] = 'instruktur/authentication/index';
