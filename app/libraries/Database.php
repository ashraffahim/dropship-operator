<?php

namespace libraries;

use PDO;

class Database {
	
	private static $host     = DB_HOST;
	private static $dbName   = DB_NAME;
	private static $username = DB_USER;
	private static $password = DB_PASS;

	private $statement;
	private $pdo;

	private $storeTable;
	private $properties;
	private $storeType;
	private $storeId;

	public $PARAM_INT = PDO::PARAM_INT;
	public $PARAM_BOOL = PDO::PARAM_BOOL;
	public $PARAM_NULL = PDO::PARAM_NULL;
	public $PARAM_STR = PDO::PARAM_STR;
	
	public function __construct() {
		$this->connect();
	}

	public function __destruct() {
		$this->pdo = null;
	}

	private function connect() {
		$this->pdo = new PDO('mysql:host='.self::$host.';dbname='.self::$dbName.';charset=utf8', self::$username, self::$password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function query($query) {
		$this->statement = $this->pdo->prepare($query);
	}

	public function bind($param, $value, $type = null) {

		if (is_null($type)) {
			switch (true) {
				case is_int($value) :
					$type = PDO::PARAM_INT;
					break;

				case is_bool($value) :
					$type = PDO::PARAM_BOOL;
					break;

				case is_null($value) :
					$type = PDO::PARAM_NULL;
					break;

				default :
					$type = PDO::PARAM_STR;
			}
		}

		$this->statement->bindParam($param, $value, $type);
	}

	public function columnNames($table) {
		$this->statement = $this->pdo->prepare('SELECT *, COUNT(0) `_CN` FROM ' . $table . ' WHERE 0 = 1');
		$this->statement->execute();
		$columnNames = $this->statement->fetch(PDO::FETCH_OBJ);
		unset($columnNames->_CN);

		return $columnNames;
	}

	public function result() {
		$this->execute();
		return $this->statement->fetchAll(PDO::FETCH_OBJ);
	}

	public function single() {
		$this->execute();
		return $this->statement->fetch(PDO::FETCH_OBJ);
	}

	public function execute() {
		$this->statement->execute();
	}

	public function rowCount() {
		return $this->statement->rowCount();
	}

	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}

	public function dispense($table, $exec = true) {
		$this->storeTable = $table;
		$this->storeType = 'INSERT';
		$this->properties = (object) [];

		if ($exec) {
			$this->properties = $this->columnNames($table);
		}

		unset($table);
		return $this->properties;
	}

	public function load($table, $id, $exec = true) {
		$this->storeTable = $table;
		$this->storeType = 'UPDATE';
		$this->storeId = $id;
		$this->properties = (object) [];

		if ($exec) {
			
			$this->statement = $this->pdo->prepare('SELECT * FROM '.$this->storeTable.' WHERE `id` = :id');
			$this->statement->bindParam(':id', $id, PDO::PARAM_INT);
			$this->statement->execute();
			$this->properties = $this->statement->fetch(PDO::FETCH_OBJ);
			unset($table);
			unset($id);

			return (object) array_merge((array) $this->properties, (array) $this->single());

		} else {
			
			return $this->properties;

		}
	}

	public function store($beans) {
		if ($this->storeType == 'INSERT') {
			
			$columns = '';
			$values = '';

			$columns = '' . implode(', ', array_keys((array) $beans)) . '';
			$values = ':' . implode(', :', array_keys((array) $beans));

			$this->query('INSERT INTO '. $this->storeTable .' ('. $columns .') VALUES ('. $values .')');

			foreach ((array) $beans as $c => $v) {
				$this->bind(':'.$c, $v);
			}

			$this->execute();

			return $this->lastInsertId();
		
		} else {

			$sets = implode(', ', array_map(
				function($c) {
					return ''.$c.' = :'.$c;
				},
				array_keys((array) $beans)
			));

			$this->query('UPDATE '. $this->storeTable .' SET '. $sets .' WHERE `id` = :id');

			foreach ((array) $beans as $c => $v) {
				$this->bind(':'.$c, $v);
			}
			$this->bind(':id', $this->storeId, PDO::PARAM_INT);

			$this->execute();

			return $this->rowCount();

		}
	}

	public function convertImageToJPG($originalImage, $outputImage, $ext, $quality) {
		if ($ext != 'jpg') {
			if (preg_match('/png/i',$ext)) :
				$imageTmp=imagecreatefrompng($originalImage);
			elseif (preg_match('/gif/i',$ext)) :
				$imageTmp=imagecreatefromgif($originalImage);
			elseif (preg_match('/bmp/i',$ext)) :
				$imageTmp=imagecreatefrombmp($originalImage);
			else :
				return 0;
			endif;

			imagejpeg($imageTmp, $outputImage, $quality);
			imagedestroy($imageTmp);
			unlink($originalImage);
		} else {
			move_uploaded_file($originalImage, $outputImage);
		}
	}

	public function view($sql) {
		return file_get_contents('../app/models/views/' . $sql . '.sql');
	}

	public function wrapMail($html) {
		$logo = LOGO;
		$eof = <<< EOF
		<div align="center" style="font-family: sans-serif">
		  <div style="max-width: 500px;width: 100%;display: inline-block">
		    <div style="background-color: #fafafa;border-radius: .25rem;border: 2px solid #ddd;padding: 2rem 0">
		      <div style="display: flex;align-items: center;justify-content: start">
		        <img src="$logo" style="height: 34px;width: 64px;border-radius: .25rem;margin-left: 2rem;">
		        <span style="margin-left: 2rem;font-size: 2rem;font-weight: 900;color: #6e4e9e"></span>
		      </div>
		      <div style="margin: 2rem 0;border: .4px solid #ccc"></div>
		      <div>
		        $html
		      </div>
		    </div>
		  </div>
		</div>
EOF;
		return $eof;
	}

	public function mail($to, $subject, $html, $text, $wrap = true, $from = false) {

		require 'PHPMailer/src/PHPMailer.php';
		require 'PHPMailer/src/Exception.php';
		require 'PHPMailer/src/SMTP.php';

		$mail = new \PHPMailer\PHPMailer\PHPMailer;

		$mail->isSMTP();
		$mail->Host = SMTP;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = false;
		$mail->Port = SMTP_PORT;

		if (is_array($from)) {
			$mail->Username = $from['username'];
			$mail->Password = $from['password'];
			$mail->setFrom($from['username'], $from['name']);
		} else {
			$mail->Username = 'verification@alghaim.com';
			$mail->Password = 'Alghaim2022';
			$mail->setFrom('verification@alghaim.com', 'Verification');
		}

		if (is_array($to)) {
			foreach ($to as $a) {
				$mail->addAddress($a);
			}
		} else {
			$mail->addAddress($to);
		}

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $wrap ? $this->wrapMail($html) : $html;
		$mail->AltBody = $text;

		if(!$mail->send()){
			return true;
		}else{
			return false;
		}

	}

}
