<?php
echo pageTitle('product/index', '<span class="float-right">'.privilegeButton('product/add').'</span>');
?>
<div class="row">
	<div class="col">
		<div class="card shadow">
			<div class="card-body">
				<div class="row">
					<div class="col"></div>
					<div class="col">
						<div class="row">
							<div class="col"><button class="btn btn-link text-danger btn-block">Disapprove</button></div>
							<div class="col"><button class="btn btn-theme btn-block">Approve</button></div>
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col"><a href="/product/spec/prev/<?php echo $data['data']->id; ?>" class="btn btn-light btn-block d-flex justify-content-between align-items-center rounded-pill"><i class="fa fa-angle-left"></i><span>Previous</span><div></div></a></div>
							<div class="col"><a href="/product/spec/next" class="btn btn-light btn-block d-flex justify-content-between align-items-center rounded-pill"><div></div><span>Next</span><i class="fa fa-angle-right"></i></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row justify-content-center mb-3">
	<div class="col-12">
	</div>
	<div class="col-lg-6 col-md-8 col-sm-10">
		<table class="table table-striped">
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
				<th>Seller</th>
				<td><?php echo $data['data']->seller . ' #' . $data['data']->sid; ?></td>

				<th>Activity</th>
				<td>
					Created: <?php echo date('d F, Y h:i:s', $data['data']->dp_timestamp); ?><br>
					Last activity: <?php echo date('d F, Y h:i:s', $data['data']->dp_latimestamp); ?>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="row justify-content-center mb-3">
	<div class="col-lg-6 col-md-8 col-sm-10">
		<span class="lead">Custom Field</span>
		<table class="table table-striped">
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
	</div>
</div>