<?php 

	$sql = "SELECT distinct designer_style FROM `item` ORDER BY designer_style";
	$query = $this->db->query($sql);

	$designer_styles = $query->result();
 ?>

 <ul>
 	<?php foreach ($designer_styles as $style) { ?>
 		<li>
 			<a target="_blank" href="<?php echo site_url('/items/audit/'.$style->designer_style)?>"><?php echo $style->designer_style ?></a>
 		</li>
 	<?php } ?>
 </ul>