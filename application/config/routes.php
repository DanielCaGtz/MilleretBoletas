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
|	http://codeigniter.com/user_guide/general/routing.html
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
/* Default */
$route['default_controller'] = 'login/ctrlogin';
$route['module_name'] = 'login/ctrlogin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['home'] = 'home/ctrhome';
$route['signout'] = 'home/ctrhome/closesession';

$route['edicion'] = 'home/ctrhome/edicion_de_boletas';
$route['edicion_sub'] = 'home/ctrhome/edicion_de_sub_aspectos';
$route['consultar'] = 'home/ctrhome/consulta_de_boletas';
$route['registro'] = 'home/ctrhome/registro_de_boletas';

$route['consulta_boleta/([A-Za-z0-9=]+)'] = 'home/ctrhome/consulta_boleta/$1';
$route['consulta_logros/([A0-9=]+)'] = 'home/ctrhome/consulta_logros/$1';
$route['editar_boleta/([A0-9=]+)'] = 'home/ctrhome/editar_boleta/$1';
$route['editar_materias'] = 'home/ctrhome/edicion_de_materias';

$route['agregar_reporte_diario'] = 'home/ctrreportes/view_agregar_reporte_diario';
$route['ver_reporte_diario'] = 'home/ctrreportes/view_ver_reporte_diario';
$route['consultar_planeacion/([A0-9=]+)'] = 'home/ctrreportes/view_consultar_planeacion/$1';

$route['reporte_detalles'] = 'home/ctrhome/view_agregar_esfuerzo';

$route['ver_usuarios'] = 'home/ctrpermisos/view_all_users';
$route['editar_usuario/([A0-9=]+)'] = 'home/ctrpermisos/view_edit_user/$1';
$route['agregar_usuario'] = 'home/ctrpermisos/view_add_user';
