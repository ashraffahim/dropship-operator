<div class="card border-0 bg-transparent mb-3">
	<div class="card-img-top position-relative">
		<div class="profile position-absolute d-flex h-100 w-100 align-items-center justify-content-center">
			<?php
			$dp = DATA . '/operator/' . $_SESSION[CLIENT . 'user_id']->id . '.jpg';
			if(file_exists(DATADIR . DS . 'operator' . DS . $_SESSION[CLIENT . 'user_id']->id . '.jpg')) {
				echo '<a href="/user/profile" data-toggle="load-host" data-target="#content"><span class="profile-image rounded-circle shadow"><img src="' . $dp . '"></span></a>';
			} else {
				echo '<a href="/user/profile" data-toggle="load-host" data-target="#content"><span class="d-inline-flex align-items-center justify-content-center bg-light text-muted h5 rounded-circle shadow m-0" style="height: 65px; width: 65px">'
				. substr($_SESSION[CLIENT . 'user_id']->name, 0, 1) . '</span></a>';
			}
			?>
			<div class="profile-description d-inline-flex flex-column text-nowrap text-dark w-50 ml-2 px-2">
				<a href="/user/profile" data-toggle="load-host" data-target="#content">
					<span class="font-weight-bold text-light"><?php echo $_SESSION[CLIENT . 'user_id']->name; ?></span>
				</a>
				<span class="font-weight-light text-light small"><?php echo $_SESSION[CLIENT . 'user_id']->position_name; ?></span>
			</div>
		</div>
		<img src="/dopamine/images/cover.png" class="cover-image" width="100%">
	</div>
</div>
<?php
@include DATADIR . DS . 'menu' . DS . 'operator-menu.php';
?>
<div class="container mt-3">
	<div class="row text-theme">
		<div class="col d-flex text-center" style="justify-content: space-evenly;">
			<a class="btn btn-icon" title="Pay For Subscription"><i class="fa fa-hand-holding-usd"></i></a>
			<a class="btn btn-icon" title="Chat with Tech Support"><i class="fa fa-life-ring"></i></a>
			<a class="btn btn-icon" title="Call Tech Support"><i class="fa fa-phone"></i></a>
		</div>
	</div>
</div>