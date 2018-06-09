<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>
<div class="actionLinks">
	<a href="<?php print site_url("admin/files/collection_add")?>" class="add newCollection" rel="shadowbox;player=iframe;width=480;height=360" style="width:87px">New Collection</a>
	<div class="collectionSearch">
		<input type="text" id="searchMedia" name="searchMedia" value="" class="formField">
		<a href="javascript:void(0)" class="searchButton" id="searchfiles">Search</a>
	</div>
</div>

<div id="mainContent">
	<div class="content" id="collectionsList">
		<h1>Collections</h1>
		<div id="messages"><?php print_messages($data)?></div>
		<?php if(count($data['collections'])>0):?>
			<table cellspacing="0" cellpadding="0" class="list">
			<thead>
				<tr>
					<th width="65%" <?php check_filter($data['sortby'], 'title')?>><a href="<?php print site_url("admin/files/collections/title/".$data['nextsortdir'])?>">Collection</a></th>
					<!--  <th width="15%" <?php check_filter($data['sortby'], 'type')?>><a href="<?php print site_url("admin/files/collections/type/".$data['nextsortdir'])?>">Type</a></th>-->
					<th width="15%" <?php check_filter($data['sortby'], 'created')?>><a href="<?php print site_url("admin/files/collections/created/".$data['nextsortdir'])?>">Date Added</a></th>
					<th width="15%" <?php check_filter($data['sortby'], 'total_files')?>><a href="<?php print site_url("admin/files/collections/total_files/".$data['nextsortdir'])?>">Articles</a></th>
				</tr>
			</thead>	
			<?php foreach ($data['collections'] AS $collection):?>
			<tr>
				<td><a href="<?php print site_url("admin/files/collection/".$collection['collection_id'])?>"><?php print $collection['title']?></a></td>
				<!--<td><?php $collection['type']=="other" ? $collection['type'] ="artwork" : ""; print ucfirst($collection['type']);?></td>-->
				<td><?php print date("m/d/Y", $collection['created']);?></td>
				<td><?php print $collection['total_files'];?></td>
			</tr>
			<?php endforeach;?>
			</table>
			<?php else:?>
			<div class="info"><p>No collections added.</p></div>	
		<?php endif;?>
	</div>
</div>