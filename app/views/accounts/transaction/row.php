<?php

foreach ($data['data'] as $i => $p) :

	?>
	<tr>
		<td><?php echo $p->id; ?></td>
		<td class="oid"><?php echo $p->p_order; ?></td>
		<td><?php echo $p->p_method; ?></td>
		<td>{<?php echo $p->p_status; ?>}</td>
		<td><?php echo date('d M, Y h:i:s', $p->p_timestamp); ?></td>
		<td><?php echo date('d M, Y h:i:s', $p->p_latimestamp); ?></td>
		<td></td>
	</tr>
	<?php

endforeach;

?>