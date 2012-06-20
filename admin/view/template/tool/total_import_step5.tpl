<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<script type="text/javascript">
function updateText() {
	$("#update_text").hide();
	if (document.settings_form.reset.value == '1') {
		$(".non_reset").hide();
	} else {
		$(".non_reset").show();
		if (document.settings_form.existing_items.value == 'update') {
			$("#update_text").show();
		}
	}
}
$(document).ready(function() {
	updateText();
});
</script>
<div class="box">
  <div class="heading">
    <h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
	<div class="buttons">Enter a name to save a new settings profile: <input type="text" name="save_settings_name" value=""><a onclick="$('#import_form').submit();" class="button"><span><?php echo $button_import; ?></span></a><a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
	<div id="tabs" class="htabs"><!-- <a href="#tab_fetch">Step 1: Fetch Feed</a><a href="#tab_adjust"><?php echo $tab_adjust; ?></a><a href="#tab_global"><?php echo $tab_global; ?></a><a href="#tab_mapping"><?php echo $tab_mapping; ?></a> --><a href="#tab_import"><?php echo $tab_import; ?></a></div>
	    <div id="tab_import">
        <table class="form">
        	<!-- Reset Store -->
        	<tr>
        		<td><?php echo $entry_reset; ?></td>
        		<td>
        			<select name="reset" onchange="updateText();">
        				<option value="0">No</option>
        				<option value="1" <?php if (isset($reset) && $reset) echo 'selected="true"'; ?>>Yes</option>
        			</select>
        		</td>
        	</tr>
        	<!-- New Items -->
        	<tr class="non_reset">
        		<td><?php echo $entry_new_items; ?></td>
        		<td>
	        		<select name="new_items">
	        			<option value="add">Add</option>
	        			<option value="skip" <?php if (isset($new_items) && $new_items == 'skip') echo 'selected="true"'; ?>>Skip</option>
	        		</select>       
        		</td>
        	</tr>
        	<!-- Existing Items -->
        	<tr class="non_reset">
        		<td><?php echo $entry_existing_items; ?></td>
        		<td>
        			<select name="existing_items">
        				<option value="update">Update</option>
        				<option value="skip" <?php if (isset($existing_items) && $existing_items == 'skip') echo 'selected="true"'; ?>>Skip</option>
        			</select>
        			<span id="update_text">
						Update Based on Matching Field: 
						<select name="update_field">
							<option value="model"><?php echo $text_field_model; ?></option>	
							<option value="sku" <?php if (isset($update_field) && $update_field == 'sku') echo 'selected="true"'; ?>><?php echo $text_field_sku; ?></option>	
							<!-- <option value="name"><?php echo $text_field_name; ?></option> -->
						</select>
					</span>
        		</td>
        	</tr>
        </table>
       </div>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
</form>
<?php echo $footer; ?>