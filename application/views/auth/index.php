
<style type="text/css">
	.row {
		padding : 20px;
	}
	.infoMessage {
		text-align: center;
		font-weight: 600;
		font-size: 2.5em;
		margin-bottom: 0;
		padding-bottom: 0;
	}

</style>


<div class="infoMessage row">
	<p>
		List of all users
	</p>
</div>


	<div class="row">
 		<div class="col-md-12">
            <div class="table table-responsive">
                <table class="table-bordered table table-bordered">
                    <tr>
                        <th>FIRST NAME  </th>
                        <th>LAST NAME</th>
                        <th>EMAIL ADDRESS</th>
                        <th>ADDRESS</th>
                        <th>GROUPS</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    <tr/>
                    <?php foreach ($users as $user):?>
						<tr>
							<td><?php echo $user->first_name;?></td>
							<td><?php echo $user->last_name;?></td>
							<td><?php echo $user->email;?></td>
							<td><?php echo $user->location;?></td>
							<td>
								<?php foreach ($user->groups as $group):?>
									<?php echo anchor("auth/edit_group/".$group->id, $group->name) ;?><br />
				                <?php endforeach?>
							</td>
							<td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
							<td><?php echo anchor("auth/edit_user/".$user->id, 'Edit') ;?></td>
						</tr>
					<?php endforeach;?>
                </table>

            </div>
        </div>
    </div>