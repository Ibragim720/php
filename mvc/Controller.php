<?php
/*
 *      Controller.php
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

class Controller
{
    private $model;
    public function __construct($model){
        $this->model = $model;
    }
    
    public function Login($sLogin, $sPasswd) {
		if ( (isset($sLogin) && !empty($sLogin)) && (isset($sPasswd) && !empty($sPasswd)) ) {
			$sLogin=trim(strip_tags($sLogin));
			$sPasswd=trim(strip_tags($sPasswd));
			return $this->model->Login($sLogin, md5($sPasswd));
		}
		return false;
    }
    public function Logout() {
		$this->model->Logout();
		return true;
    }    
    
    public function Main() {
		return $this->model->GetBalance();
	}
    
    public function CashOut() {
		return $this->model->GetBalance();
	}
    
	public function GetCash($dSum) {
		$dSum=trim(strip_tags($dSum));
		if ( isset($dSum) && !is_float($dSum) && $dSum>0 ) {
			return $this->model->GetCash($dSum);
		}
		return false;
	}
    
    public function GetUsersBalance($sPrm) {
		$sPrm=trim(strip_tags($sPrm));
		return $this->model->GetUsersBalance($sPrm);
	}    
}

?>
