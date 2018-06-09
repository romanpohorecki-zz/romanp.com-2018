<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(count($collections)>0):?>
	<table cellspacing="0" cellpadding="0" class="list collectionsList">
		<thead>
			<tr>
				<th width="70%" <?php $sort_order=="title"? print "class='selected'":""?>><a href="javascript:void(0)" rel="title">Collection</a></th>
				<th width="30%" <?php $sort_order=="collection_id"? print "class='selected'":""?>><a href="javascript:void(0)" rel="collection_id">Date</a></th>
			</tr>
		</thead>	
		<?php foreach ($collections AS $collection):?>
		<tr>
			<td><a href="<?php print $collection['collection_id']?>" class="collectionRow" style="display:block"><img src="<?php print base_url()."assets/images/cms/image16.png"?>" style="margin-right:10px;"><?php print $collection['title'];?></a></td>
			<td><a href="<?php print $collection['collection_id']?>" class="collectionRow" style="display:block"><?php print date("m/d/Y", $collection['created']);?></a></td>
		</tr>
		<?php endforeach;?>
	</table>	
<?php else:?>
	<p class="info">You don't have any collection added. Please visit /Articles section first.</p>	
<?php endif;?>