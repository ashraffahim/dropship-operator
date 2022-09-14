<?php
echo pageTitle('transactiob/index');
$url = '/transaction/row?page=' . $data['page'];
?>
<div class="row">
	<div class="col">
		<div class="card shadow">
			<div class="card-body">
				<form class="silent" data-target=".transaction-rows">
					<div class="row">
						<div class="col-sm-6">
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
								<?php echo filterDataRow('.oid'); ?>
								<input type="hidden" name="page" value="0">
							</div>
						</div>
					</div>
				</form>
			</div>

			<table class="table table-light table-striped mb-0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Order</th>
						<th>Method</th>
						<th>Status</th>
						<th>Intent</th>
						<th>Webhook</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="transaction-rows" data-url="<?php echo $url; ?>">
				<?php

				foreach ($data['data'] as $i => $p) :

					?>
					<tr>
						<td><?php echo $p->id; ?></td>
						<td class="oid"><?php echo $p->p_order; ?></td>
						<td><?php echo $p->p_method; ?></td>
						<td><div class="badge badge-<?php echo str_replace(['0', '1', '2'], ['light">Intended', 'success">Confirmed', 'warning">UC Intent'], $p->p_status); ?></div></td>
						<td><?php echo date('d M, Y h:i:s', $p->p_timestamp); ?></td>
						<td><?php echo date('d M, Y h:i:s', $p->p_latimestamp); ?></td>
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
	$('.load-more').loadMoreTableRow('.transaction-rows', function(d) {
		return d.replaceAll('{0}', '<div class="badge badge-light">Intended</div>').replaceAll('{1}', '<div class="badge badge-success">Confirmed</div>').replaceAll('{2}', '<div class="badge badge-warning">UC Intent</div>');
	});
</script>