<?php echo pageTitle('user/add'); ?>

<div class="row">
	<div class="col">
		<div class="alert alert-secondary d-flex flex-start border-dotted">
			<div class="mr-2">
				<span class="icon-stack icon-stack-3x">
					<i class="fas fa-comment-alt fa-3x text-theme-dark"></i>
					<i class="fas fa-circle fa-2x text-theme"></i>
					<i class="fa fa-info text-light"></i>
				</span>
			</div>
			<div class="flex-fill">
				<span class="h6 text-theme-darker">Effects on users</span>
				<span class="text-muted">
					<p>Users already logged into the system will not be effected.</p>
					<p><b><a href="#">Force logout users</a></b> to reload their privileges.</p>
				</span>
			</div>
		</div>
	</div>
</div>

<div class="row">

	<div class="col-xl-6">
		<div id="privilegeCard" class="card shadow">
			<div class="card-header">
				<?php echo cardTitle('user/privilege') . cardToolbar([
					'reload' => '#privilege',
					'help' => '#privilegeTag',
					'minimize' => '#privilegeCard',
					'maximize' => '#privilegeCard',
					'dock' => '#privilegeCard'
				]); ?>
			</div>
			<div id="privilege" class="card-body show" data-toggle="load" data-url="/user/privilege/<?php echo $_SESSION[CLIENT . 'user_id']->id; ?>">
			</div>
		</div>
	</div>

	<div class="col-xl-6">
		<div class="card shadow">
			<div class="card-header bg-transparent">
				<?php echo cardTitle('user/approve'); ?>
				<ul class="nav nav-tabs card-header-tabs float-right">
					<li class="nav-item"><a href="/user/approve/1" class="nav-link active" data-toggle="tab" data-target="#approve">Approve</a></li>
					<li class="nav-item"><a href="/user/approve/0" class="nav-link" data-toggle="tab" data-target="#approve">Disapprove</a></li>
				</ul>
			</div>
			<div class="card-body" id="approve" data-toggle="load" data-url="/user/approve/1"></div>
		</div>
	</div>

</div>