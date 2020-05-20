<p class="text-left" style="font-size:16px;"><strong>10 BARANG TERLARIS</strong></p>
<ul class="todo-list ui-sortable">
	<?php if($popular != null){ 
			foreach($popular as $row){
	?>
    			<li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text"><?php echo $row['nama_barang'];?></span><span class="label label-success"><?php echo $row['total'];?> ITEM</span></li>
    <?php }
	}else{ ?>
		<li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text"></span></li>
	<?php } ?>
</ul>