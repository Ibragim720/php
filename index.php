<?php
/*
 *      index.php
 *      
 *      Copyright 2017 ibragim <ibragim@server10>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

session_start();
header("Content-Type:text/html;charset=UTF-8");
 
$sDirRoot = dirname(realpath((__FILE__).'/'));

require_once($sDirRoot.'mvc/Model.php');
require_once($sDirRoot.'mvc/Controller.php');
require_once($sDirRoot.'mvc/View.php');
require_once($sDirRoot.'mvc/Logger.php');


	$model = new Model();
	$controller = new Controller($model);
	$view = new View($controller, $model);

if (isset($_GET['action']) && !empty($_GET['action'])) {
	switch ($_GET['action']) {
		case 'Login': 
			echo $view->Login( $controller->{$_GET['action']}($_POST['login'], $_POST['password']) );
			sleep(2);
		break;
		case 'Logout': 
			$controller->{$_GET['action']}();
			echo $view->Start();
		break;
		case 'GetCash': 
			echo $view->GetCash($controller->{$_GET['action']}($_POST['getcash']) );
		break;
		case 'CashOut': 
			echo $view->CashOut( $controller->{$_GET['action']}() );
		break;
		case 'Cancel': 
			echo $view->Main($controller->Main());
		break;
		
		default:
			echo $view->output();
		break;
	}
    
}
else {
	echo $view->Start();
}

?>
