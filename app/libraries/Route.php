<?php

namespace libraries;

class Route {

	protected $params;

	public function hasAccess($controller, $action) {

		$hasAccess = false;
		if ($controller.$action != '' && !in_array($controller, ['controllers\\Login', 'controllers\\Error'])) {

			$urlIndex = $controller . '/' . $action;
			$urlPrivilege = isset($_SESSION[CLIENT . 'user_privilege'][$urlIndex]) ? (bool) $_SESSION[CLIENT . 'user_privilege'][$urlIndex]->permit : false;
			$hasAccess = (isset($_SESSION[CLIENT . 'user_id']) && $_SESSION[CLIENT . 'user_id']->id == '1') ? true : $urlPrivilege;

		} else {

			$hasAccess = true;
		
		}

		return $hasAccess;

	}

	public function parseQueryString($qs) : void {
		// Query string parsing rule
		// /<controller>/<action>/[,args...]
		preg_match('/^([0-9a-z-]+|)(?:\/|)([0-9a-z-]+|)(?:\/|)(.*)$/i', $qs, $rgx);
		$this->params['controller'] = $rgx[1];
		$this->params['action'] = $rgx[2];
		$this->params['args'] = $rgx[3] != '' ? explode('/', $rgx[3]) : [];
	}

	public function dispatch($query_string) {
		$url = $this->removeQueryStringVariables($query_string);
		$this->parseQueryString($url);
		
		$controller = $this->params['controller'] != '' ? $this->params['controller'] : 'home';
		$controller = $this->convertToStudlyCaps($controller);
		$controller = 'controllers\\' . $controller;
		unset($this->params['controller']);

		$action = $this->params['action'] != '' ? $this->params['action'] : 'index';
		$action = $this->convertToCamelCaps($action);
		unset($this->params['action']);

		if (class_exists($controller)) {

			if (isset($_SESSION[CLIENT . 'user_id']) || $controller == 'controllers\\Login') {

				if ($this->hasAccess($controller, $action)) {
		
					$controller_object = new $controller();

					if (is_callable([$controller_object, $action])) {
						$arg = $this->params ? array_values($this->params['args']) : [];
						call_user_func_array([$controller_object, $action], $arg);
					}
		
				} else {
					redir("/error/ad");
				}

			} else {
				redir("/login/index");
			}

		} else {
			redir("/error/ir");
		}

	}

	public function convertToStudlyCaps($string) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	public function convertToCamelCaps($string) {
		return lcfirst($this->convertToStudlyCaps($string));
	}

	public function removeQueryStringVariables($url) {
		$parts = explode('&', $url);

		if (isset($parts[0])) {
			if (strpos($parts[0], '=') === false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}

		return $url;
	}

}

?>