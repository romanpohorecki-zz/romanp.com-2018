<div class="section">	
	<a href="javascript:void(0)" class="removeSection btn btnSize" onclick="removeSection(this)">Delete Section</a>
	<input type="hidden" name="section[<?php print $data["key"]?>][type]" value="regular"/>
	<input type="hidden" name="section[<?php print $data["key"]?>][section_id]" value="<?php print (isset($data['section_id'])) ? $data["section_id"] : 0;?>"/>
	<div class="sectionRow">		
		<label>Description</label>
		<textarea class="tinymce" name="section[<?php print $data["key"]?>][text]"><?php print (isset($data["text"])) ? $data["text"] : '';?></textarea>
	</div>
</div>