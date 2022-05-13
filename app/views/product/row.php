<?php
foreach ($data['data'] as $i => $p) :

	?>
	<tr>
		<td><?php echo $p->id; ?></td>
		<td class="name"><a href="/product/spec/<?php echo $p->id; ?>"><?php echo $p->dp_name; ?></a></td>
		<td class="name"><?php echo $p->dp_category; ?></td>
		<td><?php echo $p->dp_description; ?></td>
		<td><?php echo $p->dp_price; ?></td>
		<td><?php echo $p->dp_sellerstamp; ?></td>
		<td><?php echo date('d F, Y h:i:s', $p->dp_latimestamp); ?></td>
		<td></td>
	</tr>
	<?php

endforeach;
?>