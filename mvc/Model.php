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

class User {
	protected $aV=array();
	public function __construct($aPrm = false) {
		if(is_array($aPrm)) {
			foreach ($aPrm as $sKey => $val) {
				$this->aV[$sKey] = $val;
			}
		}
	}
	
	public function getUId() {
        return $this->aV['id'];
    }
	public function getUName() {
        return $this->aV['name'];
    }
	public function getUBalance() {
        return $this->aV['c'];
    }
	public function setUBalance($v) {
		$this->aV['c']=$v;
    }    
}

class Model
{
	public $bLogIn;
	public $Usr;
	private $dbc;
	public $Users;
	
    public function __construct(){ 
		$this->Clear();
       
		$cfg=require_once(dirname(realpath((__FILE__).'/')).'mvc/config.php');
		$this->dbc = new mysqli($cfg['servername'], $cfg['username'], $cfg['password'], $cfg['dbname']);
		if ($this->dbc->connect_error) {
			die("Connection failed: " . $this->dbc->connect_error);
		}
		if (!$this->dbc->set_charset("utf8")) {
			Logger::getLogger('log')->log("utf8: %s". $this->dbc->error);
			$this->dbc->close();
		}
		$this->dbc->autocommit(FALSE);       
    }
	public function __destruct() {
		$this->dbc->close();
	}
	
	public function Clear() {
		unset($this->Usr);
		$this->bLogIn=false;
	}
    
    public function Login($sLogin, $sPasswd) {
		$sql = 'SELECT id, name, c FROM '.USERS_TABLE.' 
				WHERE login="'.$sLogin.'" AND password="'.$sPasswd.'"';
		$result = $this->dbc->query($sql);
		
		if ($result->num_rows > 0) {
			$this->Usr=new User($result->fetch_assoc());
			$this->bLogIn=true;
			$_SESSION["UserLogin"] = true;
			$_SESSION["Usr"]=$this->Usr;
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
			$this->bLogIn=$_SESSION["UserLogin"];
			$this->Usr=$_SESSION["Usr"];
			session_write_close();
			return true;
		}
		return false;
	}
	public function GetBalance() {
		if ($this->GetLoginData()) {
			if ($this->bLogIn && $this->Usr->getUId()>0) {
				$sql = 'SELECT c FROM '.USERS_TABLE.' WHERE id='.$this->Usr->getUId();
				$result = $this->dbc->query($sql);
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$this->Usr->setUBalance($row["c"]);
					Logger::getLogger('log')->log('Get user ID='.$this->Usr->getUId().' balance = '.$this->Usr->getUBalance());
					return true;
				}
			}
		}
		$this->Usr->setUBalance(0);
		return false;
	}
    public function GetUsersBalance($sPrm='') {
		if (!empty($sPrm)) {
			$sql = 'SELECT id, name, c FROM '.USERS_TABLE.' 
				WHERE id IN('.$sPrm. ')';
		}
		else { $sql = 'SELECT id, name, c FROM '.USERS_TABLE.' LIMIT 10'; }
		$result = $this->dbc->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$this->Users[]=new User($row);
			}
			return true;
		}
		return false;
	}
    public function GetCash($dSum) {
		if (!$this->GetLoginData() || $this->Usr->getUId()==0 ) return false;
		
		//$this->dbc->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		if ( $this->dbc->query('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;') &&
			 $this->dbc->query('START TRANSACTION;') ) {
			$sql = 'SELECT c FROM '.USERS_TABLE.' WHERE id='.$this->Usr->getUId().' FOR UPDATE';
			$result = $this->dbc->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$this->Usr->setUBalance($row["c"]);
					Logger::getLogger('log')->log('start User ID='.$this->Usr->getUId().' B='.$this->Usr->getUBalance().' d='.$dSum);
				if ($this->Usr->getUBalance()>=$dSum) 
				{
					$sql = 'UPDATE '.USERS_TABLE.' SET c=(c-'.$dSum.') WHERE id='.$this->Usr->getUId().' AND c>='.$dSum ;
					if ($this->dbc->query($sql) === TRUE) {
						if ($this->dbc->commit() ) {
							$this->Usr->setUBalance( $this->Usr->getUBalance() - $dSum );
							Logger::getLogger('log')->log('commit User ID='.$this->Usr->getUId().' B='.$this->Usr->getUBalance().' d='.$dSum);
							return true;
						}
					} 
				}
			}
			$this->dbc->rollback(); 
			Logger::getLogger('log')->log('error User ID='.$this->Usr->getUId().' B='.$this->Usr->getUBalance().' d='.$dSum);
		}
		return false;
	}
}

?>
