<?php

namespace controllers;

use libraries\Controller;
use models\_User;

class Login extends Controller {

	private $user;

	function __construct() {
		$this->user = new _User();
	}
	
	public function index() {

		$data = [];

		if ($this->RMisPost()) {

			$this->sanitizeInputPost();
			$user_id = $this->user->login($_POST['user'], md5($_POST['pass']));

			if ($user_id) {
				
				$_SESSION[CLIENT . 'user_id'] = $user_id;
				$this->loadPrivilege();
				redir('/');

			} else {

				$data = [
					'error' => 'Invalid credentials',
					'redir' => '/login/index'
				];

			}
		
		}

		if (!isset($_SESSION[CLIENT . 'user_id'])) {

			$this->view('login', $data, false);
		
		} else {

			redir('/home/index');

		}

	}

	public function loadPrivilege() {

		if (isset($_SESSION[CLIENT . 'user_id']) && !isset($_SESSION[CLIENT . 'user_privilege'])) {
			$userPriv = $positionPriv = [];
			$userPriv = (array) $this->user->loadPrivilege($_SESSION[CLIENT . 'user_id']->id);
			if ($_SESSION[CLIENT . 'user_id']->position != 0) {
				$positionPriv = (array) $this->user->loadPrivilege($_SESSION[CLIENT . 'user_id']->position);
			}
			$merged = array_merge($positionPriv, $userPriv);
			$mergeFormatted = [];
			foreach ($merged as $k => $m) {
				$p = explode('/', $k);
				$i = str_replace(' ', '', ucwords(str_replace('-', ' ', $p[0]))) . '/' . lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $p[1]))));
				$mergeFormatted[$i] = $m;
			}
			$_SESSION[CLIENT . 'user_privilege'] = $mergeFormatted;
		}

		$this->checkDataDir();

	}

	public function checkDataDir() {
		if (!is_dir(DATADIR)) {
			mkdir(DATADIR);
		}
	}

	public function availability() {

		if ($this->RMisPost()) {

			$this->sanitizeInputPost();
			$data = $this->user->availability($_POST);

			if (isset($data->exists)) {
				$this->status(['status' => 1]);
			} else {
				$this->status(['status' => 0]);
			}

		}

	}

	public function signup() {

		if ($this->RMisPost()) {

			$this->sanitizeInputPost();
			$status = $this->user->signup($_POST);

			$this->status($status);
		
		}

	}

	public function logout() {
		session_destroy();
		redir('/Login/index');
	}

	public function forgotPassword() {
		$phpMailer = new \Libraries\PHPMailer\PHPMailer;
	}

}

?>