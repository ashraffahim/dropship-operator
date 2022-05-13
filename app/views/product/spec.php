<?php
echo pageTitle('product/approve');

if (isset($data['status']['status'])) {
	?>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-<?php echo $data['status']['alert']['type']; ?>">
				<h6 class="text-theme-darker">
					<?php echo $data['status']['alert']['title']; ?>
				</h6>
				<p>
					<?php echo $data['status']['alert']['body']; ?>
				</p>
			</div>
		</div>
	</div>
	<?php
	return;
}

?>

<div class="row approve-product">

	<!-- Product approval actions -->
	<div class="col-12">
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<button type="button" class="btn btn-danger rounded-pill btn-block" data-toggle="hot-post-action" data-url="/product/reject" data-data="id=<?php echo $data['data']->id; ?>" callback="redir('/product/spec/next')">
							<div class="d-flex justify-content-between">
								<div><i class="fa fa-times"></i></div>
								<b>Reject</b>
								<div></div>
							</div>
						</button>
					</div>
					<div class="col">
						<a class="btn btn-translucent rounded-pill btn-block" data-toggle="load-host" href="/product/spec/next" data-target="#content">
							<div class="d-flex justify-content-between">
								<div></div>
								<b>Skip</b>
								<div></div>
							</div>
						</a>
					</div>
					<div class="col">
						<button type="button" class="btn btn-theme rounded-pill btn-block" data-toggle="hot-post-action" data-url="/product/approve" data-data="id=<?php echo $data['data']->id; ?>" callback="redir('/product/spec/next')">
							<div class="d-flex justify-content-between">
								<div></div>
								<b>Approve</b>
								<div><i class="fa fa-check"></i></div>
							</div>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Left column -->
	<div class="col-md-6">
		<div class="row">
			<div class="col-12">
				<div class="card shadow-sm">
					<div class="card-body">
						<table class="table table-light table-striped table-lg">
							<tr>
								<th>Id</th>
								<td><?php echo $data['data']->id; ?></td>
							</tr>
							<tr>
								<th>Name</th>
								<td><?php echo $data['data']->dp_name; ?></td>
							</tr>
							<tr>
								<th>Category</th>
								<td><?php echo $data['data']->dp_category; ?></td>
							</tr>
							<tr>
								<th>Description</th>
								<td><?php echo $data['data']->dp_description; ?></td>
							</tr>
							<tr>
								<th>Price</th>
								<td><?php echo $data['data']->dp_price; ?></td>
							</tr>
							<tr>
								<th>Brand</th>
								<td><?php echo $data['data']->dp_brand; ?></td>
							</tr>
							<tr>
								<th>Model</th>
								<td><?php echo $data['data']->dp_model; ?></td>
							</tr>
							<tr>
								<th>Seller Id</th>
								<td><?php echo $data['data']->dp_sellerstamp; ?></td>
							</tr>
							<tr>
								<th>Created</th>
								<td><?php echo $data['data']->dp_timestamp; ?></td>
							</tr>
							<tr>
								<th>Last Update</th>
								<td><?php echo $data['data']->dp_latimestamp; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="card shadow-sm">
					<div class="card-body">
						<h3>Custom Specs</h3>
						<table class="table table-light table-striped table-lg">
							<?php
							foreach (json_decode($data['data']->dp_custom_field) as $f => $v) {
							?>
							<tr>
								<th><?php echo $f; ?></th>
								<td><?php echo $v; ?></td>
							</tr>
							<?php
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Right column -->
	<div class="col-md-6">
		<div class="card shadow-sm">
			<div class="card-body">
				<?php
				$files = glob(DATADIR . DS . 'draft-new-product' . DS . $data['data']->id . DS . '*');
				foreach ($files as $f) {
				?>
				<img src="<?php echo DATA . DS . 'draft-new-product' . DS . $data['data']->id . DS . basename($f); ?>">
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>