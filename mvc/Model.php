<?php
/*
 *      Model.php
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


class Model
{
	private $iUserId;
	public $sUserName;
	public $dBalance;
	public $bLogIn;
	private $dbc;
	
    public function __construct(){ 
		$this->Clear();
       
		$cfg=require_once(dirname(realpath((__FILE__).'/')).'mvc/config.php');
		$this->dbc = new mysqli($cfg['servername'], $cfg['username'], $cfg['password'], $cfg['dbname']);
		if ($this->dbc->connect_error) {
			die("Connection failed: " . $this->dbc->connect_error);
		}
		$this->dbc->autocommit(FALSE);       
    }
	public function __destruct() {
		$this->dbc->close();
	}
	
	public function Clear() {
		$this->iUserId=0;
		$this->sUserName="";
		$this->dBalance=0;
		$this->bLogIn=false;
	}
    
    public function Login($sLogin, $sPasswd) {
		$sql = 'SELECT id, name, c FROM '.USERS_TABLE.' 
				WHERE login="'.$sLogin.'" AND password="'.$sPasswd.'"';
		$result = $this->dbc->query($sql);
		
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$this->iUserId=$row["id"];
			$this->sUserName=$row["name"];
			$this->dBalance=$row["c"];
			$this->bLogIn=true;
			$_SESSION["UserLogin"] = true;
			$_SESSION["uid"] = $row["id"];
			$_SESSION["name"] = $row["name"];
			session_write_close();
			Logger::getLogger('log')->log('User '.$sLogin.' login');
			return true;
		}
		$this->Clear();
		session_unset();
		session_write_close();
		return false;
	}
	public function Logout() {
		Logger::getLogger('log')->log('User ID='.$_SESSION["uid"].' logout');
		session_unset(); 
		session_destroy();
		session_write_close();
	}
	public function GetLoginData() {
		if (isset($_SESSION["UserLogin"]) && $_SESSION["UserLogin"]) {
			$this->iUserId=$_SESSION["uid"];
			$this->sUserName=$_SESSION["name"];
			$this->bLogIn=$_SESSION["UserLogin"];
			session_write_close();
			return true;
		}
		return false;
	}
	public function GetBalance() {
		if ($this->GetLoginData()) {
			if ($this->bLogIn && $this->iUserId>0) {
				$sql = 'SELECT c FROM '.USERS_TABLE.' WHERE id='.$this->iUserId;
				$result = $this->dbc->query($sql);
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$this->dBalance = $row["c"];
					Logger::getLogger('log')->log('Get user ID='.$this->iUserId.' balance = '.$this->dBalance);
					return true;
				}
			}
		}
		$this->dBalance = 0;
		return false;
	}
    public function GetCash($dSum) {
		if (!$this->GetLoginData() || $this->iUserId==0 ) return false;
		
		//$this->dbc->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		if ( $this->dbc->query('START TRANSACTION;') ) {
			$sql = 'SELECT c FROM '.USERS_TABLE.' WHERE id='.$this->iUserId;
			$result = $this->dbc->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$this->dBalance=$row["c"];
					Logger::getLogger('log')->log('start User ID='.$this->iUserId.' B='.$this->dBalance.' d='.$dSum);
				if ($this->dBalance>=$dSum) {
					$sql = 'UPDATE '.USERS_TABLE.' SET c=(c-'.$dSum.') WHERE id='.$this->iUserId;
					if ($this->dbc->query($sql) === TRUE) {
						if ($this->dbc->commit() ) {
							sleep(2);
							$this->dBalance=$this->dBalance-$dSum;
							Logger::getLogger('log')->log('commit User ID='.$this->iUserId.' B='.$this->dBalance.' d='.$dSum);
							return true;
						}
					} 
				}
			}
			$this->dbc->rollback(); 
			Logger::getLogger('log')->log('error User ID='.$this->iUserId.' B='.$this->dBalance.' d='.$dSum);
		}
		return false;
	}
}

?>
