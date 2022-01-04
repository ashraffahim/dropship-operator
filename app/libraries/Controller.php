<?php

namespace libraries;

class Controller {

	public function view($view, $data = [], $wrap = true) {

		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$wrap = false;
		}

		if (file_exists('../app/views/' . $view . '.php')) {
			
			if ($wrap) {
			
				include '../app/views/inc/header.php';
				require_once '../app/views/' . $view . '.php';
				include '../app/views/inc/footer.php';
			
			} else {
			
				require_once '../app/views/' . $view . '.php';
			
			}

		} else {

			if ($view != '') {
				redir('/Error/ie');
			}

		}

	}

	public function status($data = []) {
		$ajax = false;

		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$ajax = true;
		}

		if ($ajax) {

			require_once '../app/views/status/json.php';
			exit;
		
		}

	}

	public function RMisPost() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') :
			return true;
		else :
			return false;
		endif;
	}

	public function get($index) {
		return isset($_GET[$index]) ? $_GET[$index] : false;
	}

	public function isPageChange() {
		return isset($_GET['page']);
	}

	public function sanitizeInputPost() {
		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	}

	public function sanitizeInputGet() {
		$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	}

	public function getLoadPage() {
		return isset($_GET['page']) ? $_GET['page'] : 0;
	}

	public function getTableRowOrder() {
		return isset($_GET['ord']) ? $_GET['ord'] : 1;
	}

}

?>