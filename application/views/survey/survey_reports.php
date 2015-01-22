<style type="text/css">
	.not-finished {
		color : red;
	}
</style>

<div class="container">
		<div class="row">
		    <div class="col-md-11 item_table">
		        <div class="tabbbleContainer">
		            <table class="table table-bordered" id="transfer_table">
		            	<thead>
			                <th>Survey id</th>
							<th>Time</th>
							<th>User</th>
							<th>Showroom</th>
							<th>Total items</th>
							<th>Missing items</th>
							<th>Status</th>
							<th>#</th>
		                </thead>

		                <tbody>
		                	<?php foreach($surveys as $s) { ?>
							<tr class="<?php echo $s->finished == 1 ? '' : 'not-finished'; ?>">
								<td><?php echo $s->survey_id ?></td>
								<td><?php echo $s->created_at ?></td>
								<td><?php echo $s->user ?></td>
								<td><?php echo $s->showroom ?></td>
								<td><?php echo $s->total_items ?></td>
								<td><?php echo $s->missing_items ?></td>
								<td><?php echo $s->finished == 1 ? 'Finished' : anchor('survey?continue=' . $s->survey_id, 'Continue', array('target' => 'blank')); ?></td>
								<td><?php echo anchor('survey/report_print/'.$s->survey_id, 'Report') ?></td>
							</tr>
							<?php } ?>

						</tbody>
		            </table>
		            <a id="bottomOfDiv"></a>
		        </div>
		    </div>
		</div>




</div>