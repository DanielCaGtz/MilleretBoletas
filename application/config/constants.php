<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Modificar con ruta completa del sistema hasta la carpeta raíz
defined('FILE_ROUTE_FULL')     OR define('FILE_ROUTE_FULL','/usr/local/var/www/milleret_boletas/');

//Versión del sistema a utilizar
defined('CURRENT_VERSION')		OR define('CURRENT_VERSION','1.2.1');

//Nombre de base de datos principal
defined('DATABASE_1')			OR define('DATABASE_1','milleret_adminbi');

//Nombre de base de datos alterna (opcional)
defined('DATABASE_2')			OR define('DATABASE_2','boletas');

//Habilita el uso de habilidades dentro del sistema en caso de ser 1. Se deshabilita con 0.
defined('JUST_ABILITIES')		OR define('JUST_ABILITIES',1);

//Habilita el uso de logros con decimales dentro del sistema en caso de ser 1. Se deshabilita con 0.
defined('LOGROS_CON_DECIMALES')	OR define('LOGROS_CON_DECIMALES',1);

//Habilita la posibilidad de consultar el detalle de las boletas dentro del sistema en caso de ser 1. Se deshabilita con 0.
defined('CONSULTA_DETALLE_BOLETA')	OR define('CONSULTA_DETALLE_BOLETA',1);

//Habilita que los grados de KINDER usen letras en lugar de números dentro del sistema en caso de ser 1. Se deshabilita con 0.
defined('LETRAS_KINDER')		OR define('LETRAS_KINDER',1);

//Habilita la visualización de reportes diarios de planeación dentro del sistema en caso de ser 1. Se deshabilita con 0.
defined('USES_PLANEACION')		OR define('USES_PLANEACION',0);

//Define el nombre que tendrán los periodos de las boletas. e.g. trimestre, bimestre, cuatrimestre, etc.
defined('BIMESTRE_NAME')		OR define('BIMESTRE_NAME','trimestre');

//Define la cantidad de periodos que se contemplerán en el sistema
defined('BIMESTRE_COUNT')		OR define('BIMESTRE_COUNT',3);

//modules
//Define el nombre de la escuela en cuestión
defined('SCHOOL_NAME')			OR define('SCHOOL_NAME','MILLERET');

//Define si se usa o no hash para el ID de alumnos en la consulta de boletas
defined('USE_HASH')				OR define('USE_HASH',FALSE);

//Habilita la posibilidad de descargar boletas en forma de PDF
defined('PDF_DOWNLOAD')			OR define('PDF_DOWNLOAD',TRUE);

//Define la URL que se usará para descargar PDF's (en caso de aplicar). Ruta completa + /consulta_boleta/
defined('URL_PDF_SOURCE')		OR define('URL_PDF_SOURCE',"http://milleret.softandgo.com/consulta_boleta/");

//Define clave de API para descarga de PDF's
defined('CURLOPT_USERPWD_')		OR define('CURLOPT_USERPWD_',"d040be8028904fec80d42681aea06381");

//Habilita la posibilidad de cargar archivos de calificaciones
defined('HAS_FILE_UPLOAD')		OR define('HAS_FILE_UPLOAD',TRUE);

//Habilita la posiblidad de usar subhabilidades dentro del sistema
defined('HAS_SUBHABIL')			OR define('HAS_SUBHABIL',TRUE);
