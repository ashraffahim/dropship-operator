<?php
echo pageTitle('product/index');

// if drafts exist
if (isset($data['data']->id)) {
?>
<div class="row" data-plugin="pa">
	<div class="col">
		<div class="card shadow">
			<div class="card-body">
				<div class="row">
					<div class="col"></div>
					<div class="col">
						<div class="row">
							<div class="col"><button class="btn btn-link text-danger btn-block disapprove-draft">Disapprove</button></div>
							<div class="col"><button class="btn btn-theme btn-block approve-draft" data-approve="<?php echo $data['data']->id; ?>">Approve</button></div>
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col"><a href="/product/approve/prev/<?php echo $data['data']->id; ?>" data-toggle="load-host" data-target="#content" class="btn btn-translucent btn-block d-flex justify-content-between align-items-center rounded-pill"><i class="fa fa-angle-left"></i><span>Previous</span><div></div></a></div>
							<div class="col"><a href="/product/approve/next" data-toggle="load-host" data-target="#content" class="btn btn-translucent btn-block d-flex justify-content-between align-items-center rounded-pill"><div></div><span>Next</span><i class="fa fa-angle-right"></i></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row justify-content-center mb-3">
	<div class="col-lg-6 col-md-8 col-sm-10">
		<table class="table table-striped table-light">
			<tr>
				<th>Name</th>
				<td><?php echo $data['data']->dp_name; ?></td>

				<th>Handle</th>
				<td><?php echo $data['data']->dp_handle; ?></td>
			</tr>
			<tr>
				<th>Category</th>
				<td><?php echo $data['data']->dp_category; ?></td>

				<th>Price</th>
				<td><?php echo $data['data']->dp_price; ?></td>
			</tr>
			<tr>
				<th>Brand</th>
				<td><?php echo $data['data']->dp_brand; ?></td>

				<th>Model</th>
				<td><?php echo $data['data']->dp_model; ?></td>
			</tr>
			<tr>
				<th>Seller</th>
				<td><?php echo $data['data']->seller . ' #' . $data['data']->sid; ?></td>

				<th>Activity</th>
				<td>
					Created: <?php echo date('d F, Y h:i:s', $data['data']->dp_timestamp); ?><br>
					Last activity: <?php echo date('d F, Y h:i:s', $data['data']->dp_latimestamp); ?>
				</td>
			</tr>
			<tr>
				<th>Description</th>
				<td colspan="3"><?php echo $data['data']->description; ?></td>
			</tr>
		</table>
	</div>
</div>
<div class="row justify-content-center mb-3">
	<div class="col-lg-6 col-md-8 col-sm-10">
		<span class="lead">Category Specification</span>
		<table class="table table-striped table-light">
			<?php
			$css = json_decode($data['data']->dp_category_spec);
			foreach ($css as $c => $s) {
				echo '<tr>
				<th>' . $c . '</th>
				<td>' . $s . '</td>
				</tr>';
			}
			?>
		</table>
	</div>
</div>
<div class="row justify-content-center mb-3">
	<div class="col-lg-6 col-md-8 col-sm-10">
		<span class="lead">Custom Field</span>
		<table class="table table-striped table-light">
			<?php
			$cfs = json_decode($data['data']->dp_custom_field);
			foreach ($cfs as $cf => $v) {
				echo '<tr>
				<th>' . $cf . '</th>
				<td>' . $v . '</td>
				</tr>';
			}
			?>
		</table>
	</div>
</div>
<div class="row justify-content-center">
	<div class="col-lg-6 col-md-8 col-sm-10">
		<?php
		
		$imgs = str_replace(DATADIR.DS.'draft'.DS.$data['data']->id.DS, DATA.'/draft/'.$data['data']->id.'/', glob(DATADIR.DS.'draft'.DS.$data['data']->id.DS.'*'));
		foreach ($imgs as $img) {
			echo '<div class="row mb-3"><div class="col-12"><img src="' . $img . '?qlt=50" class="sci"><span class="float-right"><label class="checkbox"><input type="checkbox" class="spc"><span>Clear</span></label></span></div></div>';
		}

		?>
	</div>
</div>
<style>
	.sci {
		max-height: 300px;
		max-width: 300px;
	}
</style>
<?php } else { ?>

<!-- If draft doest not exist / alert -->

<div class="row">
	<div class="col">
		<div class="card shadow">
			<div class="card-body">
				<div class="row">
					<div class="col"></div>
					<div class="col"></div>
					<div class="col">
						<div class="row">
							<div class="col"><a href="/product/approve/next" data-toggle="load-host" data-target="#content" class="btn btn-light btn-block d-flex justify-content-between align-items-center rounded-pill"><div></div><span>Check for updates</span><i class="fa fa-angle-right"></i></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-lg-4 col-md-6 col-sm-8">
		<div class="card-tag card-tag-<?php echo $data['data']['card-tag']['type']; ?>">
			<?php echo $data['data']['card-tag']['body']; ?>
		</div>
	</div>
</div>

<?php } ?>