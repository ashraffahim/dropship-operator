<form method="post" action="/user/approve/<?php echo $data['status']; ?>" class="silent" callback="$('#approve').loadFrameURL()">
	<table class="table table-sm table-light table-hover">
		<thead>
			<tr>
				<th class="bg-light text-center">
					<label class="checkbox m-0 p-0"><input type="checkbox" onchange="$('.approve').toggleCheck(this)"><span><span></label>
				</th>
				<th>Name</th>
				<th>Username</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($data['data'] as $user) :
				
				echo "<tr>
				<td class=\"bg-light text-center\"><label class=\"checkbox m-0 p-0\"><input type=\"checkbox\" name=\"approve[]\" value=\"$user->id\" class=\"approve\"><span><span></label></td>
				<td>$user->first_name $user->last_name</td>
				<td>$user->username</td>
				<td>$user->email</td>
				</tr>";

			endforeach;
			?>
		</tbody>
	</table>
	<button type="submit" class="btn btn-theme float-right">Update</button>
</form>