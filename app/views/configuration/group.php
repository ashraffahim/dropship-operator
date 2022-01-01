<div class="card-header bg-transparent">
	<?php

	echo "Groups" . cardToolbar([
		'reload' => '#groups'
	]);

	?>
</div>
<div class="list-group list-group-flush py-3">
	<?php foreach ($data['data'] as $group) : ?>
		<a class="list-group-item" data-toggle="load-host" data-url="/Configuration/index/item/<?php echo $group->id . '/' . $group->id . '/' . $group->title; ?>" data-target="#items" id="gl<?php echo $group->id; ?>">
			<?php
			echo $group->title . ' (#'.$group->id.')';
			echo makeDropdown('<i class="fa fa-ellipsis-v fa-xs"></i>', [
				'Edit' => 'data-toggle="edit" data-id="'.$group->id.'"',
				'Remove' => 'data-toggle="confirm" data-body="Are you you want to remove Group?" data-url="/configuration/index/remove" data-id="'.$group->id.'" callback="$(\'#groups\').loadFrameURL()"'
			], 'button', 'btn-xs btn-icon');
			?>
		</a>
	<?php endforeach; ?>
</div>
<div id="addItem">
<hr>
<form id="group-form" method="post" action="/Configuration/index/update" callback="$('#groups').loadFrameURL()" class="silent px-3 py-2">
<div class="form-group">
	<input type="hidden" name="is" value="1">
	<div class="form-group">
		<div class="input-group">
			<input type="text" name="title" class="form-control" placeholder="Title">
		</div>
	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn btn-theme"><i class="fa fa-plus"></i> Add</button>
	</div>
</div>
</form>
</div>
<script type="text/javascript">
	$('#groups [data-toggle="edit"]').click(function() {
		$('#group-form .input-group .input-group-prepend').remove();
		$('#group-form .input-group').prepend('<div class="input-group-prepend" onclick="$(this).remove()">'
		+'<span class="input-group-text">'+$(this).data('id')+'</span>'
		+'<input type="hidden" name="id" value="'+$(this).data('id')+'">'
		+'</div>');
	});
</script>