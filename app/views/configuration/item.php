<div class="card-header bg-transparent">
<?php if($data['group'] != $data['id']) : ?>
<a  href="/configuration/index/item/<?php echo $data['group'] . '/' . $data['group'] . '/' . $data['group']; ?>" class="btn btn-xs btn-icon float-left mr-2" data-toggle="load-host" data-target="#items"><i class="fa fa-angle-left fa-lg"></i></a> 
<?php endif; ?>
<?php echo 'Submenu of <i class="font-weight-light">' . $data['name'] . '</i>' . cardToolbar(['reload' => '#items']); ?>

</div>
<div class="card-body">
<table class="table table-sm table-light table-hover">
	<?php
		$attr = '';
		foreach ($data['data'] as $i => $item) :
			$attr = $data['group'] == $data['id'] ? " data-toggle=\"load-host\" data-url=\"/configuration/index/item/$item->id/{$data['group']}/$item->title\" data-target=\"#items\" id=\"gl$item->id\"" : '';
			?>
			
			<tr>
				<?php
				echo "<td $attr>" . ++$i . "</td><td $attr>$item->title</td><td $attr>$item->icon</td><td $attr>$item->query_string</td><td $attr>" . (($item->is == 10 || $item->is == 11) ? '<i class="fa fa-eye-slash"></i>' : '') . "</td><td>$item->root</td><td $attr>$item->position</td><td>";
				echo makeDropdown('<i class="fa fa-ellipsis-v fa-xs"></i>', [
					'Edit' => 'data-toggle="edit" data-id="'.$item->id.'"',
					'Remove' => 'data-toggle="confirm" data-body="Are you you want to remove Item?" data-url="/configuration/index/remove" data-id="'.$item->id.'" callback="$(\'#items\').loadFrameURL()"'
				], 'button', 'btn-xs btn-icon') . '</td>';
				?>
				</tr>
		<?php endforeach; ?>
</table>
<form id="item-form" method="post" action="/configuration/index/update" callback="$('#items').loadFrameURL()" class="silent">
	<div class="row">
		<div class="col-sm">
			<div class="form-group">
				<div class="input-group m-0">
					<input type="text" name="title" class="form-control" placeholder="Title">
				</div>
			</div>
			<div class="form-group">
				<input type="text" name="icon" class="form-control" placeholder="Icon">
			</div>
			<div class="form-group">
				<input type="text" name="query_string" class="form-control" placeholder="Query String">
			</div>
		</div>
		<div class="col-sm">
			<div class="form-group">
				<input type="text" name="root" class="form-control" value="<?php echo $data['id']; ?>" readonly="">
			</div>
			<div class="form-group">
				<input type="number" name="position" class="form-control" placeholder="Position">
			</div>
			<div class="form-group">
				<label class="checkbox"><input type="checkbox" name="hidden" value="1"><span>Is hidden</span></label>
				<label class="checkbox"><input type="checkbox" name="open" value="1"><span>Is open</span></label>
				<button type="submit" class="btn btn-theme float-right"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
</li>
</div>
</form>
<script type="text/javascript">
	$('#items [data-toggle="edit"]').click(function() {
		$('#item-form .input-group .input-group-prepend').remove();
		$('#item-form .input-group').prepend('<div class="input-group-prepend" onclick="$(this).remove()">'
		+'<span class="input-group-text">'+$(this).data('id')+'</span>'
		+'<input type="hidden" name="id" value="'+$(this).data('id')+'">'
		+'</div>');
	});
</script>