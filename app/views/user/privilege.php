<div id="privilegeTag" class="card-tag card-tag-info collapse">
	<ol class="pl-3 m-0">
		<li>Use permit <b>checkbox</b> to grant/restrict access</li>
		<li>Remove permit <code><i class="far fa-trash-alt"></i></code> to restrict access and use presets</li>
		<li>Privilege assigned to user's role are presets and can be overwritten by adding new privilege row and modified</li>
		<li>To make a <i>user</i> appear as <abbr title="Privilege presets">role</abbr> for other users, leave the <label for="role-option"><a>role option</a></label> blank</li>
	</ol>
</div>

<form method="post" action="/User/privilege/<?php echo $data['uid']; ?>" class="silent" callback="$('#privilege').loadFrameURL()">
	<div class="form-group row">
		<div class="col">
			<label for="user-option">Privilege for</label>
			<?php echo makeSelectOption('name="uid" id="user-option"', $data['userOption'], $data['uid'], false, 'user'); ?>
		</div>
		<div class="col">
			<label for="role-option">User's role</label>
			<?php echo makeSelectOption('name="position" id="role-option"', $data['positionOption'], $data['userPosition'], true); ?>
		</div>
	</div>

	<table class="table table-sm table-light table-hover">
		<thead>
			<tr>
				<th>Filename</th>
				<th class="bg-light text-center"><label class="checkbox m-0 p-0"><input type="checkbox" onchange="$('.permit').toggleCheck(this)"><span></span></label></th>
				<th></th>
			</tr>
		</thead>
		<tbody >
			<?php
			$gi = 0;
			foreach ($data['data'] as $i => $nav) :
			$gi = $i;
			?>
			<tr id="pr<?php echo $i; ?>">
				<td>
					<input type="hidden" name="row[]" value="<?php echo $i; ?>">
					<select name="nav_<?php echo $i; ?>" class="w-100 border-0">
						<?php echo makeOption($data['navOption'], $nav->nav); ?>
					</select>
				</td>
				<td class="bg-light text-center">
					<label class="checkbox m-0 p-0"><input type="checkbox" name="permit_<?php echo $i; ?>" class="permit" <?php echo $nav->permit ? 'checked' : ''; ?>><span></span></label>
				</td>
				<td><button class="btn btn-xs btn-icon btn-light text-danger hover float-right" onclick="$('#pr<?php echo $i; ?>').remove()" data-title="Remove"><i class="far fa-trash-alt"></i></button></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<button type="button" class="add-row btn btn-outline-secondary rounded-circle"><i class="fa fa-plus"></i></button>
	<button type="submit" class="btn btn-theme float-right">Update</button>
</form>
<script type="text/javascript">
	var row, i = <?php echo ++$gi ?>;
	$('.user').change(function() {
		$('#privilege').loadInto('/User/privilege/' + $(this).val());
	});
	$('.add-row').click(function() {
		row = '<tr id="pr'+i+'">'
				+'<td><input type="hidden" name="row[]" value="'+i+'">'
				+'<select name="nav_'+i+'" class="w-100 border-0"><?php echo makeOption($data['navOption']); ?></select></td>'
				+'<td class="bg-light text-center"><label class="checkbox m-0 p-0"><input type="checkbox" name="permit_'+i+'" class="permit"><span></span></label></td>'
				+'<td><button class="btn btn-xs btn-icon btn-light text-danger hover float-right" onclick="$(\'#pr'+i+'\').remove()" data-title="Remove"><i class="far fa-trash-alt"></i></button></td>'
			+'</tr>';
		$('#privilege .table tbody').append(row);
		i++;
	});
</script>