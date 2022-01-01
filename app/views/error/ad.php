<?php include 'header.php'; ?>
<div class="login-main">
	<div class="col-md-6 col-sm-12">
		<div class="login-form">
			<p class="display-4">Access denied!</span>
			<p class="lead">The page you have requested can not be displayed as:
				<ul>
					<li>Your access is restricted</li>
				</ul>
			</p>
			<p class="lead">You have access to:
				<ul>
				<?php
				foreach ($_SESSION[CLIENT . 'user_privilege'] as $priv) :
					if ($priv->permit) :
				?>
					<li><a href="/<?php echo $priv->query_string; ?>"><?php echo $priv->title; ?></a></li>
				<?php
					endif;
				endforeach;
				?>
				</ul>
			</p>
			<button class="btn btn-primary w-50" onclick="window.history.back()">Back</button>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>