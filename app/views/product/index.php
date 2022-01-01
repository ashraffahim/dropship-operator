<?php
echo pageTitle('product/index', '<span class="float-right">'.privilegeButton('product/add').'</span>');
$url = '/product/approve'.($data['id'] ? '/' . $data['id'] : '').'?ord='.$data['ord'];
?>
<div class="row">
	<div class="col">
		<div class="card shadow">
			<div class="card-body">
				<form class="silent" data-target=".product-rows">
					<div class="row">
						<div class="col-md">
							<div class="row justify-content-start">
								<div class="col-md-4 col-sm-8">
									<?php echo dataTableOrderBy(['Id', 'Name', 'Price']); ?>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="row justify-content-end">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="checkbox float-right">
											<input type="checkbox" name="self" value="1">
											<span>Added by current account</span>
										</label>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<?php
										// echo makeCustomSelectOption('name="type"', $data['product_type']);
										?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<?php echo filterDataRow('.name'); ?>
										<input type="hidden" name="page" value="0">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>

			<table class="table table-light table-striped mb-0">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th>Name</th>
						<th>Handle</th>
						<th>Category</th>
						<th>Price</th>
						<th>Seller</th>
						<th>Last Activity</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="product-rows" data-url="<?php echo $url; ?>">
				<?php

				foreach ($data['data'] as $i => $p) :
					
					$img = glob(DATADIR . DS . 'draft' . DS . $p->id . DS . '*')[0];

					?>
					<tr>
						<td><?php echo $p->id; ?></td>
						<td><img src="<?php echo DATA . '/draft/' . $p->id . '/' . basename($img) . '?qlt=0'; ?>" height="24" class="product-image rounded" data-id="<?php echo $p->id; ?>"></td>
						<td class="name"><a href="/product/approve/<?php echo $p->id; ?>"><?php echo $p->dp_name; ?></a></td>
						<td><?php echo $p->dp_handle; ?></td>
						<td class="name"><?php echo $p->dp_category; ?></td>
						<td><?php echo $p->dp_price; ?></td>
						<td><?php echo $p->seller; ?></td>
						<td><?php echo date('d F, Y h:i:s', $p->dp_latimestamp); ?></td>
						<td></td>
					</tr>
					<?php

				endforeach;

				?>
				</tbody>
			</table>

		</div>
	</div>
</div>
<div class="row justify-content-center">
	<div class="col-md-2">
		<button class="load-more btn btn-translucent btn-lg btn-block shadow">More</button>
	</div>
</div>
<script type="text/javascript">
	$('.load-more').loadMoreTableRow('.product-rows');
	$('.product-rows').on('click', '.product-image', function() {
		makeModal('', '', '<img src="' + $(this).attr('src').replace('qlt=0', 'qlt=75') + '" class="w-100 rounded">');
	});
</script>