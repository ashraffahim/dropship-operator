<?php

if (!$data['data']) {
	?>
	<div class="alert alert-secondary d-flex border-dotted">
		<div class="mr-2">
			<span class="icon-stack icon-stack-3x">
				<i class="fa fa-circle fa-3x text-theme-darker"></i>
				<i class="fa fa-circle fa-2x text-theme"></i>
				<i class="fa fa-user-slash fa-lg text-light"></i>
			</span>
		</div>
		<div class="flex-fill">
			<span class="h6 text-theme-darker">Invalid arguments</span>
			<p class="text-muted">User, <i><b>Id: </b><?php echo $data['id']; ?></i>, does not exists</p>
		</div>
	</div>
	<?php
	exit;
}

if (isset($_GET['edit']) && $data['data']->id == $data['uid']) {

	$removeProfileImage = '';
	$dp = DATA . '/operator/' . $data['data']->id . '.jpg';
	if(file_exists(DATADIR . DS . 'operator' . DS . $data['data']->id . '.jpg')) {
		$image = '<label class="profile-image d-inline-block position-relative rounded-circle shadow"><img src="' . $dp . '" style="height: 100px; width: 100px"><input type="file" name="image" accept="image/*" id="profile-image-input" class="d-none" onchange="previewInputImage(this, \'.profile-image img\')"><i class="edit-image position-absolute fa fa-camera bg-theme-dark rounded-circle p-2" style="bottom: 0;right: 0"></i></label>';
		$removeProfileImage = '<a href="#" data-toggle="shortcut-post-action" data-url="/user/profile/remove-profile-image" callback="makeToast(\'Profile Update\', \'trash-alt\', \'Profile picutre is removed. <b>Reload page</b>\')" class="btn btn-outline-danger">Remove profile image</a>';
	} else {
		$image = '<label class="d-inline-flex position-relative align-items-center justify-content-center bg-light text-muted rounded-circle shadow m-0 pb-2" style="height: 100px; width: 100px"><span class=" display-4">'
		. substr($data['data']->first_name, 0, 1) . '</span><input type="file" name="image" accept="image/*" id="profile-image-input" class="d-none" onchange="previewInputImage(this, \'.profile-image img\')"><i class="edit-image position-absolute fa fa-camera bg-theme-dark rounded-circle p-2" style="bottom: 0;right: 0"></i></label>';
	}

	$name = '<div class="row">
	<div class="col-md-6 form-group">
	<label for="first_name">First Name</label>
	<input type="text" name="first_name" id="first_name" value="' . $data['data']->first_name . '" class="form-control">
	</div>
	<div class="col-md-6 form-group">
	<label for="last_name">Last Name</label>
	<input type="text" name="last_name" value="'.$data['data']->last_name.'" class="form-control"></div>
	</div>';
	$position = $data['data']->position_first_name === null ? 'Independent' : $data['data']->position_first_name . ' ' . $data['data']->position_first_name;
	$position = '';
	$username = '<div class="row">
	<div class="col-md-6 form-group">
	<label for="username">Username</label>
	<input type="text" name="username" id="username" value="' . $data['data']->username . '" class="form-control">
	</div>';
	$email = '<div class="col-md-6 form-group">
	<label for="email">Email</label>
	<input type="email" name="email" id="email" value="' . $data['data']->email . '" class="form-control">
	</div></div>
	<div class="row">
	<div class="col-md-6">
	' . $removeProfileImage . '
	</div>
	<div class="col-md-6 text-right">
	<a href="/user/profile" class="btn btn-translucent" data-toggle="load-host" data-target="#content">Discard</a>
	<button type="submit" class="btn btn-theme">Save changes</button>
	</div></div>';

} else {

	$dp = DATA . '/operator/' . $data['data']->id . '.jpg';
	if(file_exists(DATADIR . DS . 'operator' . DS . $data['data']->id . '.jpg')) {
		$image = '<span class="profile-image d-inline-block rounded-circle shadow"><img src="' . $dp . '" style="height: 100px; width: 100px"></span>';
	} else {
		$image = '<span class="d-inline-flex align-items-center justify-content-center bg-theme text-light display-4 rounded-circle shadow m-0 pb-2" style="height: 100px; width: 100px">'
		. substr($data['data']->first_name, 0, 1) . '</span>';
	}

	$name = '<div class="h3">' . $data['data']->first_name . ' ' . $data['data']->last_name . '
	<span class="text-muted"> @' . $data['data']->username . '</span>
	' . ($data['data']->id == $data['uid'] ? '<a href="/user/profile&edit" class="btn btn-icon text-muted" data-toggle="load-host" data-target="#content"><i class="fa fa-pen"></i></a>' : '') . '
	</div>';
	$position = $data['data']->position_first_name === null ? 'Independent' : $data['data']->position_first_name . ' ' . $data['data']->position_last_name;
	$position = '<small class="text-muted"><i>' . $position . '</i></small>';
	$username = '';
	$email = '<div><a href="mailto:' . $data['data']->email . '">' . $data['data']->email . '</a></div>';

}

?>
<div class="row mb-4">
	<div class="col-lg-8">
		<div class="card shadow h-100">
			<form method="post" enctype="multipart/form-data" class="silent">
				<div class="card-body profile">
					<div class="card-tags"></div>
					<div class="row">

						<div class="col-md-3">
							<?php echo $image; ?>
						</div>

						<div class="col-md-9">
							<?php echo $name; ?>
							<?php echo $position; ?>
							<?php echo $username; ?>
							<?php echo $email; ?>
						</div>

					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card shadow h-100">
			<div class="card-body">
				<div class="row">
					<div class="col d-inline-flex lead align-items-center">Google Account</div>
					<div class="col d-inline-flex lead justify-content-end align-items-center">
						<img src="/dopamine/images/illustration/google-g.png" height="20">
						<img src="/dopamine/images/illustration/google-mail.png" height="24" class="ml-2">
						<img src="/dopamine/images/illustration/google-drive.png" height="24" class="ml-2">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">

	<?php if ($data['data']->id == $data['uid']) : ?>

		<div class="col-lg-5">
			<div class="card shadow">
				<div class="card-lock" onclick="$(this).remove()"></div>
				<div class="card-body">

					<span class="icon-stack icon-stack-3x float-right">
						<i class="fa fa-circle fa-3x text-theme"></i>
						<i class="fa fa-sync-alt fa-3x text-theme-darker"></i>
						<i class="fa fa-lock fa-1x text-light"></i>
					</span>
					<div class="display-4 mb-4">Change Password</div>

					<form method="post" action="/user/profile/change-password" class="silent">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="new-password">New Password</label>
									<input type="password" name="new_password" id="new-password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="confirm-password">Confirm Password</label>
									<input type="password" name="confirm_password" id="confirm-password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="old-password">Old Password</label>
									<input type="password" name="old_password" id="old-password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
							</div>
							<div class="col-md-12">
								<label class="checkbox"><input type="checkbox" class="show-password"><span> Show password</span></label>
								<button type="submit" class="btn btn-theme float-right">Change password</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	<?php endif; ?>

	<div class="col-lg-7">
		<div class="card shadow">
			<div class="card-body">
				<span class="icon-stack icon-stack-3x float-right">
					<i class="fa fa-circle fa-3x text-theme-dark"></i>
					<i class="fa fa-circle fa-2x text-theme"></i>
					<i class="far fa-user fa-lg text-light"></i>
				</span>
				<div class="display-4">Pesonalize</div>
				<span class="lead">Lorem ipsum</span>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">

	$('.show-password').change(function() {
		if ($(this).is(':checked')) {
			$('#new-password, #confirm-password').attr('type', 'text');
		} else {
			$('#new-password, #confirm-password').attr('type', 'password');
		}
	});

</script>