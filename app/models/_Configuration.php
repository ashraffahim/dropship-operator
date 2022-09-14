<?php

namespace models;

use libraries\Database;

class _Configuration {

	private $db;

	public function __construct() {
		$this->db = new Database();
	}

	public function navigationView($id = 0, $hidden = false) {
		$this->db->query('SELECT * FROM `sys_nav` WHERE ' . ($hidden ? '`is` < 10 AND' : '') . ($id == 0 ? ' `root` IS NULL' : ' `root`="'.$id.'"') . ' ORDER BY `position` DESC');

		return $this->db->result();
	}

	public function navigationUpdate($data) {

		$this->db->query('UPDATE `sys_nav` SET `title`=:title, `icon`=:icon, `query_string`=:query_string, `root`=:root, `position`=:position, `is`=:is WHERE `id`=:id');
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':icon', $data['icon']);
		$this->db->bind(':query_string', $data['query_string']);
		$this->db->bind(':root', $data['root']);
		$this->db->bind(':position', $data['position']);
		$this->db->bind(':is', (isset($data['hidden']) ? 1 : 0) . '' . (isset($data['open']) ? 1 : 0));
		$this->db->bind(':id', $data['id']);

		$this->db->execute();

		return [
			'toast' => [
				'title' => 'Configurations',
				'icon' => 'cogs',
				'body' => 'Update successful'
			]
		];

	}

	public function navigationInsert($data) {

		$this->db->query('INSERT INTO `sys_nav` (`title`, `icon`, `query_string`, `root`, `position`, `is`) VALUES (:title, :icon, :query_string, :root, :position, :is)');
		$this->db->bind(':title', $data['title']);
		$this->db->bind(':icon', $data['icon']);
		$this->db->bind(':query_string', $data['query_string']);
		$this->db->bind(':root', $data['root']);
		$this->db->bind(':position', $data['position']);
		$this->db->bind(':is', (isset($data['hidden']) ? 1 : 0) . '' . (isset($data['open']) ? 1 : 0));

		$this->db->execute();

		return [
			'toast' => [
				'title' => 'Configurations',
				'icon' => 'cogs',
				'body' => 'New menu added'
			]
		];
		
	}

	public function navigationRemove($id) {

		$this->db->query('DELETE FROM `sys_nav` WHERE `id`=:id');
		$this->db->bind(':id', $id);

		$this->db->execute();

	}

}

?>