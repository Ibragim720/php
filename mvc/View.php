<?php
/*
 *      View.php
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

class View
{
    private $model;
    private $controller;
    private $sH;
    private $sE;
    public function __construct($controller,$model) {
        $this->controller = $controller;
        $this->model = $model;
        $this->sH= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
			<head> <title>test</title><meta http-equiv="content-type" content="text/html;charset=utf-8" /> </head>
			<body>';
		$this->sE= '</body> </html>';
    }
    public function Start() {
        return $this->sH.
			'<form action="/index.php?action=Login" method="post">
				<fieldset>
				Login:<br>
				<input type="text" name="login" value="user1">
				<br>
				Password:<br>
				<input type="text" name="password" value="user1">
				<br><br>
				<input type="submit" value="Вход">
				</fieldset>
			</form>'.$this->sE ;
    }   

    public function Main($bR) {
		if (!$bR) {
			return $this->sH.
				'<H2>Welcome '.$this->model->sUserName.'</H2>'.
				'<p>Баланс: '.$this->model->dBalance.'</p>'.
				'<p><b>ошибка</b></p>'.
				'<p><a href="index.php?action=CashOut">Вывести</a></p>'.
				'<p><a href="index.php?action=Logout">Выход</a></p>'
			.$this->sE ;
		}
		else {
			return $this->sH.
				'<H2>Welcome '.$this->model->sUserName.'</H2>'.
				'<p>Баланс:'.$this->model->dBalance.'</p>'.
				'<p></p>'.
				'<p><a href="index.php?action=CashOut">Вывести</a></p>'.
				'<p><a href="index.php?action=Logout">Выход</a></p>'
			.$this->sE ;
		}
	}     
    
    public function Login($bLogIn) {
		if ($bLogIn) { return $this->Main(true); }
		return $this->sH.'<p><a>Login error</a> </br> <a href="index.php">Start</a></p>'.$this->sE ;
    }      
    
    public function GetCash($bGetCash) {
		if ($bGetCash) {
			return $this->Main(true);
		}    
		return $this->Main(false);
	}
    
	public function CashOut() {
		return $this->sH.
			'<p>Баланс: '.$this->model->dBalance.'</p> </br>'.
			'<form action="/index.php?action=GetCash" method="post">
				Вывести:
				<input type="text" name="getcash" value="5">
			<input type="submit" value="Ok">
			</form>'.
			'<p><a href="index.php?action=Cancel">Отмена</a></p>'.$this->sE ;
	}
}

?>
