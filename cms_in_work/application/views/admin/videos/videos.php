<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="messages"><?php print_messages($data)?></div>
<?php if(count($data['videos'])>0):?>
	<ul class="list">
		<li class="listHead">
			<div style="width:80%"><span>Title</span></div>
			<div style="width:20%"><span></span></div>
		</li>	
		<?php foreach ($data['videos'] AS $video):?>
		<li class="listRow" rel="<?php print $video["video_id"]?>">
			<div style="width:80%"><span><?php print $video['title']?></span></div>
			<div style="width:20%;text-align:right">
				<span><a href="<?php print site_url('admin/videos/play/'.$video['video_id'])?>" class="deleteImage" rel="shadowbox;player=iframe;width=800;height=600">play</a> | 
				<a href='javascript:void(0);' onclick="javascript:deleteVideo('<?php print $video['video_id']?>')" class="deleteImage">delete</a></span>
			</div>
		</li>
		<?php endforeach;?>
	</ul>
	<div class="pagination" style="display:none;">
		<div class="paginationInfo">Page <?php print $data['cur_page']?> of <?php print $data['total_pages'];?></div>
		<div class="paginationLinks"><?php print $data['pagination'];?></div>
	</div>		
<?php else:?>
	<div class="info"><p>No videos to display.</p></div>	
<?php endif;?>