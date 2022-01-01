<?php

// Global variables
$CSO = 0;
$PDD = '';

function redir($url) {
	header('location: ' . $url);
}

function ts($t) {
	return date('d F, Y h:i:s', $t);
}

function df($t) {
	return date('d F, Y', $t);
}

function validateInput($validation, $input) {
	$v = [
		'required' => '/.+/',
		'integer' => '/^[0-9]*$/',
		'rInteger' => '/^[0-9]+$/',
		'decimal' => '/^[0-9]*(?:|\.)[0-9]*$/',
		'rDecimal' => '/^[0-9]+(?:|\.)[0-9]*$/',
		'text' => '/^[a-z]*$/i',
		'rText' => '/^[a-z]+$/i',
		'email' => '/^(?:[a-z0-9._]+@\w+\.\w+|)$/i',
		'rEmail' => '/^[a-z0-9._]+@\w+\.\w+$/i',
		'phone' => '/^(?:\+|)[0-9]*$/',
		'rPhone' => '/^(?:\+|)[0-9]+$/'
	];

	return (bool) preg_match($v[$validation], $input);
}

function fileGetJSON($url) {
	$data = file_get_contents($url);
	return json_decode($data, true);
}

function cardTitle($query_string) {
	if (isset($_SESSION[CLIENT . 'user_privilege'][$query_string])) {
		return '<b>' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->root . '</b> <i class="font-weight-light">' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->title . '</i>';
	}
}

function pageTitle($query_string, $inline = '') {
	if (isset($_SESSION[CLIENT . 'user_privilege'][$query_string])) {
		return '<div class="row"><div class="col mb-3"><span class="lead hover-btn-icon-toggle ml-3">
		<i class="' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->icon . ' text-muted"></i>
		<a href="javascript: window.history.back()">
		<i class="fa fa-arrow-circle-left text-theme"></i>
		</a>
		</span>
		<span class="lead text-muted"><b>' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->root . '</b> <i>' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->title . '</i></span></div><div class="col">' . $inline . '</div></div>';
	}
}

function filterDataRow($filter, $class = '') {
	return '<input type="text" class="form-control ' . $class . '" placeholder="Filter" onkeyup="filterDataRow(this)" filter="' . $filter . '">';
}

function makeDropdownItem($arr = [], $elem = 'a') {

	$dropdown = '';
	foreach($arr as $title => $attr) {
		$dropdown .= '<' . $elem . ' type="button" class="dropdown-item" '.$attr.'>'.$title.'</' . $elem . '>';
	}

	return $dropdown;

}

function makeDropdown($title, $arr = [], $elem = 'a', $btnclass = '', $class = '') {

	$dropdown = '';

	if (!empty($arr)) {
		$dropdown = '<div class="dropdown float-right ' . $class . '">
		<button type="button" class="btn ' . $btnclass . '" data-toggle="dropdown">
		' . $title . '
		</button>
		<div class="dropdown-menu dropdown-menu-right">' . makeDropdownItem($arr, $elem) . '</div></div>';
	}

	return $dropdown;

}

function makeOption($data, $select = '', $blank = false) {
	$opt = '';
	if ($blank) {
		$opt .= '<option></option>';
	}
	foreach ($data as $key => $o) {
		if (is_array($o)) {
			$opt .= '<optgroup label="'.$key.'">' . makeOption($o, $select) . '</optgroup>';
		} else {
			$opt .= '<option value="'.$o->val.'" '.($o->val == $select ? 'selected' : '').'>'.$o->data.'</option>';
		}
	}
	return $opt;
}

function makeSelectOption($attr, $data, $select = '', $blank = false, $class = '') {
	$select = '<select class="custom-select ' . $class . '" ' . $attr . '>' . makeOption($data, $select, $blank) . '</select>';
	return $select;
}

function makeCustomSelectOption($attr, $data, $select = '', $blank = false, $filter = false, $class = '', $btnclass = 'btn-translucent') {
	global $CSO;
	$CSO++;

	$dropdown = '<div class="dropdown select ' . $class . '" data-selected=' . $select . '>
		<button type="button" class="btn btn-block text-left ' . $btnclass . '" data-toggle="dropdown">Select</button>
		<div class="dropdown-menu w-100" id="CSO' . $CSO . '">
		' . ($filter ? '<div class="form-group p-2 m-0">' . filterDataRow('#CSO' . $CSO . ' label span', 'form-control-sm') . '</div>' : '');
		
		if ($blank) {
			$dropdown .= '<label type="button" class="w-100"><input type="radio" ' . $attr . ' value=""><span class="dropdown-item">&nbsp;</span></label>';
		}

		foreach($data as $v) {
			$dropdown .= '<label type="button" class="w-100 m-0"><input type="radio" ' . $attr . ' value="' . $v->val . '"><span class="dropdown-item">' . $v->data . '</span></label>';
		}

		$dropdown .= '</div></div>';
	return $dropdown;
}

function privilegeDropdown($option = [], $param) {

	global $PDD;
	$dropdown = $PDD;
	if ($dropdown == '') {
		foreach ($option as $i => $p) {

			$qs = $p;

			if (isset($_SESSION[CLIENT . 'user_privilege'][$qs])) {
				if ($_SESSION[CLIENT . 'user_privilege'][$qs]->permit == 1) {
					
					if ((bool) preg_match('/\/remove$/i', $qs)) {
					
						$attr = 'data-toggle="confirm" data-title="Removing Group" data-icon="warn" data-body="Are you you want to remove Group?" data-url="/Configuration/remove" data-id="' . '{id}' . '" callback="$(\'#row' . '{id}' . '\').remove()"';
					
						$title = $_SESSION[CLIENT . 'user_privilege'][$qs]->title;
					
					} else {

						$attr = 'href="/'.$_SESSION[CLIENT . 'user_privilege'][$qs]->query_string.'/' . '{id}' . '" data-toggle="load-host" data-target="#content"';

						$title = $_SESSION[CLIENT . 'user_privilege'][$qs]->title;
					
					}

					$dropdown .= '<a class="dropdown-item" '.$attr.'>'.$title.'</a>';
				}
			}
		}
		$PDD = $dropdown;
	}

	if ($dropdown != '') {
		$dropdown = '<div class="dropdown float-right">
		<button type="button" class="btn btn-xs btn-icon" data-toggle="dropdown">
		<i class="fa fa-ellipsis-v"></i>
		</button>
		<div class="dropdown-menu dropdown-menu-right">' . str_replace('{id}', $param, $PDD) . '</div></div>';
	}

	return $dropdown;

}

function privilegeButton($query_string) {
	if (isset($_SESSION[CLIENT . 'user_privilege'][$query_string])) {
		return '<a href="/' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->query_string . '" data-toggle="load-host" data-target="#content" class="btn btn-info">' . '<b>' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->root . '</b> <i class="font-weight-light">' . $_SESSION[CLIENT . 'user_privilege'][$query_string]->title . '</i>' . '</a>';
	}
}

function cardToolbar($tools = []) {

	$pile = '<span class="card-tools float-right">';
	$extras = '';

	foreach ($tools as $tool => $action) {
		switch ($tool) {
			case 'maximize':
				$pile .= '<button type="button" class="btn btn-icon btn-xs text-warning" data-toggle="maximize" data-target="'.$action.'" data-title="Maximize"><i class="fa fa-circle"></i></button>';
				break;

			case 'minimize':
				$pile .= '<button type="button" class="btn btn-icon btn-xs text-success" data-toggle="minimize" data-target="'.$action.'" data-title="Minimize"><i class="fa fa-circle"></i></button>';
				break;

			case 'reload':
				$extras .= '<button class="dropdown-item" data-toggle="reload" data-target="'.$action.'">Reload</button>';
				break;

			case 'help':
				$extras .= '<button class="dropdown-item" data-toggle="slide" data-target="'.$action.'">Help</button>';
				break;

			case 'dock':
				$pile .= '<button class="btn btn-icon btn-xs text-danger" data-toggle="close" onclick="$(\''.$action.'\').remove()" data-title="Close"><i class="fa fa-circle"></i></button>';
				$extras .= '<button class="dropdown-item" data-toggle="dock" data-target="'.$action.'">Dock</button>';
				break;

			default:
				$extras .= '<button class="dropdown-item" '.$action.'>'.$tool.'</button>';
		}
	}

	if ($extras != '') {
		$extras = '<div class="dropdown float-left">
		<button type="button" class="btn btn-icon btn-xs text-muted" data-toggle="dropdown" data-title="More">
		<i class="fa fa-angle-down fa-lg"></i></button><div class="dropdown-menu dropdown-menu-right">' . $extras . '</div></div>';
	}

	return $pile . $extras . '</span>';
}

function dataTableOrderBy($columns) {
	$pile = '<div class="dropdown">
		<button data-toggle="dropdown" class="btn btn-translucent btn-xs">Order by <i class="fa fa-angle-down"></i></button>
		<div class="dropdown-menu">';
	$dropdown = [];
	$i = 1;
	foreach ($columns as $key => $value) {
		$pile .= '<div class="dropdown-item pr-1">
			' . $value . '
			<span class="float-right hover">
				<label class="radio">
					<input type="radio" name="ord" value="' . $i . '">
					<span class="btn btn-icon btn-xs"><i class="fa fa-arrow-up"></i></span>
				</label>';
		$i++;
		$pile .='<label class="radio">
					<input type="radio" name="ord" value="' . $i . '">
					<span class="btn btn-icon btn-xs"><i class="fa fa-arrow-down"></i></span>
				</label>
			</span>
		</div>';
		$i++;
	}
	$pile .= '</div>
		</div>';
	return $pile;
}

?>