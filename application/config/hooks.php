<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
/*
$hook['pre_controller'] = array(
	'class'		=> 'checkSession',
	'function'	=> 'check',
	'filename'	=> 'checkSession.php',
	'filepath'	=> 'hooks'
);
/* */

/* GEO-LOCALIZATION HOOKS *
$hook['post_controller_constructor'][] = array(
	'class' => 'Country',
	'function' => 'checkCountry',
	'filename' => 'Country.php',
	'filepath' => 'hooks'
);
$hook['post_controller_constructor'][] = array(
	'class' => 'Country',
	'function' => 'IP2',
	'filename' => 'Country.php',
	'filepath' => 'hooks'
);
$hook['post_controller_constructor'][] = array(
	'class' => 'Country',
	'function' => 'IPDB',
	'filename' => 'Country.php',
	'filepath' => 'hooks'
);

/* LANGUAGE HOOK */


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

?>