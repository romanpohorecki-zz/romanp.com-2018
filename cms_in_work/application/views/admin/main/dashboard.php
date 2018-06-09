<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="breadcrumbs"><?php print $breadcrumbs?></div>

<div id="mainContent" class="noActionLinks fixedPosition">
	<div class="content" id="dashboard">
		<p>Hello <strong><?php print $this->session->userdata('lastname').' '.$this->session->userdata('firstname')?></strong>,</p>
		<p>Welcome to Admin Panel</p>		
	</div>
</div>	