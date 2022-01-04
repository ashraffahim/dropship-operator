<?php

namespace controllers;

use libraries\Controller;
use models\_Configuration;

class Configuration extends Controller {

	private $config;

	public function __construct() {
	
		$this->config = new _Configuration();

	}

	public function index($t = false, $id = false, $g = false, $n = false) {

		switch ($t) {
			case false: {
				
				$this->view('configuration/configuration');

			} break;

			case 'group': {
				$this->group();
			} break;

			case 'item': {
				$this->item($id, $g, $n);
			} break;

			case 'update': {
				$this->update();
			} break;

			case 'remove': {
				$this->remove();
			} break;
		}


	}

	public function group() {

		$this->view('configuration/group', ['data' => $this->config->navigationView()]);

	}

	public function item($id, $g, $n) {

		$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
		$this->view('configuration/item', [
			'data' => $this->config->navigationView($id),
			'id' => $id,
			'group' => $g,
			'name' => $n
		]);

	}

	public function update() {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			foreach (['id', 'title', 'icon', 'query_string', 'root', 'position', 'is'] as $key) {
				$_POST[$key] = isset($_POST[$key]) ? $_POST[$key] : null;
			}

			if ($_POST['id'] != null) {

				$status = $this->config->navigationUpdate($_POST);
				$this->createMenu();
				$this->status($status);
			
			} else {

				$status = $this->config->navigationInsert($_POST);
				$this->createMenu();
				$this->status($status);

			}

		}


	}

	public function remove() {
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
			$this->config->navigationRemove($_POST['id']);

			$this->createMenu();

		}

	}

	public function createMenu() {
		$pile = '';
		$titles = $this->config->navigationView();
		foreach($titles as $group) {

			$pile .= ( $group->title != '' ? '<h6 class="sidebar-heading text-uppercase px-4 mt-4 mb-2"><span>'.$group->title.'</span></h6>' : '' ) . '<ul class="nav flex-column">';

			$roots = $this->config->navigationView($group->id, true);
			foreach($roots as $item) {

				$query_strings = $this->config->navigationView($item->id, true);

				if(empty($query_strings)) {

					$pile .= '<li class="nav-item"><a class="nav-link" data-toggle="load-host" href="/'.$item->query_string.'" data-target="#content"><span class="btn-icon btn-xs mr-2"><i class="'.$item->icon.' fa-lg"></i></span><span>'.$item->title.'</span></a></li>';

				} else {

					$pile .= '<li class="nav-item"><a tabindex="0" class="nav-link"><span class="btn-icon btn-xs mr-2"><i class="'.$item->icon.' fa-lg"></i></span><span>'.$item->title.'</span></a><ul class="nav flex-column">';

					foreach ($query_strings as $qs) {
						$pile .= '<li class="nav-item"><a class="nav-link" data-toggle="load-host" href="/'.$qs->query_string.'" data-target="#content"><span class="btn btn-icon btn-xs"><i class="fa"></i></span><span>'.$qs->title.'</span></a></li>';
					}

					$pile .= '</ul></li>';

				}

			}

			$pile .= '</ul>';

		}

		file_put_contents(DATADIR . DS . 'menu' . DS . 'menu.php', $pile);
	}

}

?>