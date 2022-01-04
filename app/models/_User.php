<?php

namespace models;

use libraries\Database;

class _User {
	
	private $db;

	public function __construct() {
		$this->db = new Database();
	}

	public function login($user, $pass) {
		$this->db->query('SELECT `id`, `position`, CONCAT(`first_name`, " ", `last_name`) AS name, CONCAT(`position_first_name`, " ", `position_last_name`) AS `position_name` FROM (' . $this->db->view('user') . ') `user` WHERE `username` = :user AND `password` = :pass AND `permit` = 1');
		$this->db->bind(':user', $user, $this->db->PARAM_STR);
		$this->db->bind(':pass', $pass, $this->db->PARAM_STR);

		return $this->db->single();
	}

	public function availability($data) {

		$this->db->query('SELECT `id` AS `exists` FROM `operator` WHERE '.(isset($data['username']) ? '`o_username`' : '`o_email`').' = :data');
		$this->db->bind(':data', (isset($data['username']) ? $data['username'] : $data['email']), $this->db->PARAM_STR);

		return $this->db->single();
	
	}

	public function signup($data) {

		if (isset($this->availability(['username' => $data['username']])->exists)) {
			return [
				'toast' => [
					'title' => 'Signup',
					'icon' => 'user',
					'body' => 'Username exists. Try using a different username.'
				]
			];
		}

		if (isset($this->availability(['email' => $data['email']])->exists)) {
			return [
				'toast' => [
					'title' => 'Signup',
					'icon' => 'user',
					'body' => 'Email exists. Contact admin if password needs to be changed.'
				]
			];
		}


		$this->db->query('INSERT INTO `operator` (`o_first_name`, `o_last_name`, `o_username`, `o_password`, `o_email`, `o_position`, `o_permit`) VALUES (:first_name, :last_name, :username, :password, :email, 0, 0)');
		$this->db->bind(':first_name', $data['first_name'], $this->db->PARAM_STR);
		$this->db->bind(':last_name', $data['last_name'], $this->db->PARAM_STR);
		$this->db->bind(':username', $data['username'], $this->db->PARAM_STR);
		$this->db->bind(':password', md5($data['password']), $this->db->PARAM_STR);
		$this->db->bind(':email', $data['email'], $this->db->PARAM_STR);

		$this->db->execute();

		return [
			'toast' => [
				'title' => 'Signup',
				'icon' => 'user',
				'body' => 'Signup complete! Awaiting confirmation.'
			]
		];
	
	}

	public function waitingApproval($status) {

		$this->db->query('SELECT `id`, `o_first_name`, `o_last_name`, `o_username`, `o_email` FROM `operator` WHERE `o_permit` = :status AND NOT `id` = 1');
		$this->db->bind(':status', (bool) $status ? 0 : 1, $this->db->PARAM_INT);
		return $this->db->result();

	}

	public function approve($data, $status) {
		
		foreach ($data['approve'] as $id) {
			$this->db->query('UPDATE `operator` SET `o_permit` = :status WHERE `id` = :id');
			$this->db->bind(':status', $status, $this->db->PARAM_INT);
			$this->db->bind(':id', $id, $this->db->PARAM_INT);
			$this->db->execute();

			return [
				'toast' => [
					'title' => 'User Update',
					'icon' => 'user',
					'body' => 'Users have been ' . ($status ? 'approved' : 'disapproved')
				]
			];
		}
	
	}

	public function userPosition($id) {
		$this->db->query('SELECT `o_position` FROM `operator` WHERE `id` = :id');
		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		return $this->db->single();
	}

	public function userPositionOption($role = false, $self) {

		$this->db->query('SELECT `id` AS `val`, CONCAT(`o_first_name`, " ", `o_last_name`) `data` FROM `operator` WHERE ' . ($role ? '' : 'NOT') . ' `o_position` = 0 AND `o_permit` = 1');
		if ($role) {
			$this->db->bind(':self', $self, $this->db->PARAM_INT);
		}
		return $this->db->result();

	}

	public function navOption() {
		
		$this->db->query('SELECT `id` `val`, CONCAT(`query_string`, ": ", `title`) `data` FROM `sys_nav` WHERE (NOT `is` = 11 AND NOT `is` = 1) AND NOT `query_string` = ""');
		return $this->db->result();

	}

	public function privilege($id) {

		$this->db->query('SELECT `nav`, `permit` FROM `sys_privilege` WHERE `uid` = :id ORDER BY `nav`');
		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		return $this->db->result();

	}

	public function privilegeUpdate($data) {

		$uid = $data['uid'];
		$position = $data['position'];

		$this->db->query('DELETE FROM `sys_privilege` WHERE `uid` = :id');
		$this->db->bind(':id', $uid, $this->db->PARAM_INT);
		$this->db->execute();

		if (isset($data['row'])) {
			foreach ($data['row'] as $row) {
				$nav = $data['nav_'.$row];
				$permit = isset($data['permit_'.$row]) ? 1 : 0;
				$this->db->query('INSERT INTO `sys_privilege` (`uid`, `nav`, `permit`) VALUES (:uid, :nav, :permit)');
				$this->db->bind(':uid', $uid, $this->db->PARAM_INT);
				$this->db->bind(':nav', $nav, $this->db->PARAM_INT);
				$this->db->bind(':permit', $permit, $this->db->PARAM_INT);
				$this->db->execute();
			}
		}

		$this->db->query('UPDATE `operator` SET `o_position` = :position WHERE `id` = :id');
		$this->db->bind(':position', $position, $this->db->PARAM_INT);
		$this->db->bind(':id', $uid, $this->db->PARAM_INT);
		$this->db->execute();

		return [
			'toast' => [
				'title' => 'Privilege Update',
				'icon' => 'tasks',
				'body' => 'Privileges have been updated'
			]
		];

	}

	public function loadPrivilege($id) {

		if ($id == '1') {
			$this->db->query('SELECT DISTINCT `nav`.`title`, `root`.`title` AS `root`, `nav`.`icon`, `nav`.`query_string`, "1" AS `permit` FROM `sys_nav` `nav` JOIN `sys_nav` `root` ON (`nav`.`root` = `root`.`id`) WHERE NOT `nav`.`query_string` = "" OR NOT `nav`.`query_string` = NULL');
		} else {
			$this->db->query('SELECT `title`, `root_name` AS `root`, `icon`, `query_string`, `permit` FROM (' . $this->db->view('privilege') . ') `nav` WHERE `uid` = :id OR (`uid` = 0)');
			$this->db->bind(':id', $id, $this->db->PARAM_INT);
		}
		$privilege = [];
		foreach ($this->db->result() as $key => $value) {
			$privilege = array_merge($privilege, [$value->query_string => $value]);
		}
		return $privilege;

	}

	public function profile($id) {
		$this->db->query('SELECT * FROM (' . $this->db->view('user') . ') `operator` WHERE `id` = :id');
		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		return $this->db->single();
	}

	public function profileUpdate($data, $file, $id) {
		
		$usernameAvaiability = $this->availability(['username' => $data['username']]);
		$usernameAvaiability = isset($usernameAvaiability->exists) ? $usernameAvaiability->exists : false;
		$emailAvaiability = $this->availability(['email' => $data['email']]);
		$emailAvaiability = isset($emailAvaiability->exists) ? $emailAvaiability->exists : false;

		if ((bool) $usernameAvaiability && $usernameAvaiability != $id) {
			return [
				'card-tag' => [
					'type' => 'warning',
					'body' => '<b>Username is in use!</b> Try another one'
				]
			];
		}

		if ((bool) $emailAvaiability && $emailAvaiability != $id) {
			return [
				'card-tag' => [
					'type' => 'warning',
					'body' => '<b>Email is in use!</b> Please check again'
				]
			];
		}

		if ($file['tmp_name'] != '') {

			if ($file['error'] == 0 && in_array(pathinfo($file['name'])['extension'], ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif', 'svg'])) {
				
				$ext = pathinfo($file['name'])['extension'];

				$originalImage = $file['tmp_name'];
				$outputImage = DATADIR . DS . 'operator' . DS . $id . '.jpg';
				$quality = 75;

				$this->db->convertImageToJPG($originalImage, $outputImage, $ext, $quality);

			} else {
				return [
					'card-tag' => [
						'type' => 'danger',
						'body' => 'The file you\'ve uploaded is <b>corrupted</b>'
					]
				];
			}

		}

		$this->db->query('UPDATE `operator` SET `o_first_name` = :first_name, `o_last_name` = :last_name, `o_username` = :username, `o_email`= :email WHERE `id` = :id');
		$this->db->bind(':first_name', $data['first_name'], $this->db->PARAM_STR);
		$this->db->bind(':last_name', $data['last_name'], $this->db->PARAM_STR);
		$this->db->bind(':username', $data['username'], $this->db->PARAM_STR);
		$this->db->bind(':email', $data['email'], $this->db->PARAM_STR);
		$this->db->bind(':id', $id, $this->db->PARAM_INT);

		$this->db->execute();

		return [
			'toast' => [
				'title' => 'Profile',
				'icon' => 'user',
				'body' => 'Your profile is updated'
			],
			'reload' => '#content'
		];

	}

	public function changePassword ($data, $id) {

		$this->db->query('SELECT `id` AS `verify` FROM `operator` WHERE `id` = :id AND `o_password` = :pass');
		$this->db->bind(':id', $id, $this->db->PARAM_INT);
		$this->db->bind(':pass', md5($data['old_password']), $this->db->PARAM_STR);

		if (isset($this->db->single()->verify)) {
			
			if ($data['new_password'] === $data['confirm_password']) {
				$this->db->query('UPDATE `operator` SET `o_password` = :pass WHERE `id` = :id');
				$this->db->bind(':pass', md5($data['new_password']), $this->db->PARAM_STR);
				$this->db->bind(':id', $id, $this->db->PARAM_INT);

				$this->db->execute();

				return [
					'toast' => [
						'title' => 'Password Change',
						'icon' => 'key',
						'body' => 'Password change was successful'
					],
					'form' => [
						'reset' => true
					]
				];
			} else {
				return [
					'toast' => [
						'title' => 'Password Change',
						'icon' => 'key',
						'body' => '<b>New Password</b> and <b>Confirm Password</b> did not match, try again'
					]
				];
			}

		} else {
			return [
				'toast' => [
					'title' => 'Password Change',
					'icon' => 'key',
					'body' => '<b>Old Password</b> was incorrect'
				]
			];
		}

	}

}

?>