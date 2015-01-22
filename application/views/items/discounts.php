<style type="text/css">
	.discount-container {
		padding-left: 100px;
		padding-right: 100px;
	}
	td, tr, th {
		text-align: center;
	}
</style>

<div class="row">
	<div class="col-xs-12 discount-container">
		<?php echo form_open('items/discount'); ?>
			<table class="table table-bordered">
				<thead>
					<th>Designer Style</th>
					<th>Amount (%)</th>
				</thead>
				<tbody>
					<?php foreach($discounts as $discount): ?>
					<tr>
						<td>
							<label for=""><?php echo $discount->designer_style; ?></label>
						</td>
						<td>
							<input type="text" name="<?php echo $discount->designer_style; ?>" value="<?php echo $discount->percent; ?>">
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
				<tfoot>
					<input type="hidden" value="hey" name="allow" />
					<tr>
						<td>
							<input type="submit" value="Submit" class="btn">
						</td>
					</tr>
				</tfoot>
			</table>		
		</form>
	</div>
</div>