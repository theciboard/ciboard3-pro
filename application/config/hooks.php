<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
	'class'			=> '_Common',
	'function'		=> 'init',
	'filename'		=> 'Common.php',
	'filepath'		=> 'hooks'
);

$hook['post_controller'][] = array(
	'class'			=> '_Stat',
	'function'		=> 'init',
	'filename'		=> 'Stat.php',
	'filepath'		=> 'hooks'
);

$hook['post_system'][] = array(
	'class'			=> '_Scheduler',
	'function'		=> 'init',
	'filename'		=> 'Scheduler.php',
	'filepath'		=> 'hooks'
);

$hook['post_system'][] = array(
	'class'			=> '_Member_dormant',
	'function'		=> 'init',
	'filename'		=> 'Member_dormant.php',
	'filepath'		=> 'hooks'
);
