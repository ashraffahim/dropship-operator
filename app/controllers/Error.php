<?php

namespace Controllers;

use Libraries\Controller;

class Error extends Controller {
	
	function __construct() {

		$errors = [
			'Error/ad' => [
				'alert' => [
					'type' => 'secondary',
					'title' => '',
					'body' => '<div class="mr-2">
					<span class="icon-stack icon-stack-3x">
						<i class="fa fa-circle fa-3x text-theme-dark"></i>
						<i class="fa fa-circle fa-2x text-theme"></i>
						<i class="fa fa-user-slash fa-lg text-light"></i>
					</span></div>
					<div class="flex-fill">
					<span class="h6 text-theme-darker">Access Denied</span>
					<p class="text-muted">Your access is restricted by the admin.<br>Learn about your privileges from your <b><a href="/User/profile" data-toggle="load-host" data-target="#content">profile</a></b>.</p>'
				]
			],
			'Error/ir' => [
				'alert' => [
					'type' => 'danger',
					'title' => '',
					'body' => '<div class="mr-2">
					<span class="icon-stack icon-stack-3x">
						<i class="fa fa-circle fa-3x text-theme-darker"></i>
						<i class="fa fa-circle fa-2x text-theme"></i>
						<i class="fa fa-file-alt fa-lg text-light"></i>
						<i class="fa fa-slash fa-lg text-theme-darker"></i>
					</span></div>
					<div class="flex-fill">
					<span class="h6 text-theme-darker">Invalid Request</span>
					<p class="text-muted">The requested page is not found.</p>'
				]
			],
			'Error/ie' => [
				'alert' => [
					'type' => 'danger',
					'title' => 'Internal Error',
					'body' => 'The requested page has internal error'
				]
			]
		];
		
		$this->status($errors[explode('&', $_SERVER['QUERY_STRING'])[0]]);
		$this->view(strtolower($_SERVER['QUERY_STRING']), [], false);
	}

}

?>