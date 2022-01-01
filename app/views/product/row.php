<?php

foreach ($data['data'] as $i => $p) :
	
	$img = glob(DATADIR . DS . 'product' . DS . $p->id . DS . '*')[0];

	?>
	<tr>
		<td><?php echo $p->id; ?></td>
		<td><img src="<?php echo DATA . '/product/' . $p->id . '/' . basename($img) . '?qlt=0'; ?>" height="24" class="product-image rounded" data-id="<?php echo $p->id; ?>"></td>
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