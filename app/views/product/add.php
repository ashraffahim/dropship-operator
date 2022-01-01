<?php echo pageTitle('product/add'); ?>
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card shadow">
			<form method="post" action="/Product/add" enctype="multipart/form-data" class="silent">
				<div class="card-body">
					<div class="card-tags"></div>
					<div class="row">
						<div class="col-lg">
							<div class="form-group">
								<label for="product-image">
									<img src="/dopamine/images/illustration/photo.png" id="product-image-preview" class="d-block rounded border-1" style="filter: drop-shadow(0 0 white)" height="100">
								</label>
								<input type="file" name="image" accept="image/*" id="product-image" class="d-none" onchange="previewInputImage(this, '#product-image-preview')">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label class="font-weight-bold">Type of product</label>
								<?php
								echo makeCustomSelectOption('name="type"', $data['product_type'], 1, false, false, '', 'btn-info btn-lg font-weight-bold');
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg">
							<div class="form-group">
								<label for="product-name">Name</label>
								<input type="text" name="name" id="product-name" class="form-control" validate="required">
							</div>
						</div>
						<div class="col-lg">
							<div class="form-group">
								<label for="product-upc">UPC</label>
								<input type="text" name="upc" id="product-upc" class="form-control" validate="decimal">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg">
							<div class="form-group">
								<label for="product-max-price">Max Price</label>
								<input type="text" name="max_price" id="product-max-price" class="form-control" validate="rDecimal">
							</div>
						</div>
						<div class="col-lg">
							<div class="form-group">
								<label for="product-tax">Tax</label>
								<input type="text" name="tax" value="0" id="product-tax" class="form-control">
								<small class="form-text">Leave blank or 0 for no tax</small>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg">
							<div class="form-group">
								<label for="product-size">Size / Unit</label>
								<span data-toggle="popover" title="Usage of Size / Unit" data-content="<?php echo file_get_contents('../app' . DOPDATA . 'popover/product-size-unit.html'); ?>" tabindex="0">
									<i class="fa fa-question-circle ml-1"></i>
								</span>
								<div class="input-group mb-3">
									<input type="text" name="size" id="product-size" class="form-control" validate="required">
									<div class="input-group-append">
										<?php echo makeSelectOption('name="unit" validate="required"', $data['unit_option'], '', true, 'input-group-text'); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg">
							<div class="form-group">
								<label>Status</label>
								<label class="checkbox d-block"><input type="checkbox" name="status" checked disabled>
									<span>Active</span>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group d-flex justify-content-end">
								<button class="btn btn-theme">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>