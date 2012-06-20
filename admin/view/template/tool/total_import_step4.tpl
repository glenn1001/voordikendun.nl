<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
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
	function addSub(el) {
		sub = $(el).closest('.hori').children('td').children('select').first().clone();
		$(el).before(sub);
		return false;
	}
	function addVert(el, multi) {
		newEl = '<tr class="vert';
		if (multi) {
			newEl += ' hori';
		}
		newEl += '">' + $(el).closest('.vert').html() + '</tr>';
		if (multi == true) {
			matches = newEl.match(/\]\[(\d+)\]\[\]/);
			count = parseInt(matches[1]);
			count = count + 1;
			newEl = newEl.replace(']['+(count-1).toString()+'][]', ']['+count.toString()+'][]');
		}
		$(el).hide();
		$(el).closest('.vert').after(newEl);
		return false;
	}
</script>
<div class="box">
  <div class="heading">
    <h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#import_form').submit();" class="button"><span><?php echo $button_next; ?></span></a><a href="<?php echo $skip_url; ?>" class="button"><span><?php echo $button_skip; ?></span></a><a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  	<div id="tabs" class="htabs"><!-- <a href="#tab_fetch">Step 1: Fetch Feed</a><a href="#tab_adjust"><?php echo $tab_adjust; ?></a><a href="#tab_global"><?php echo $tab_global; ?></a> --><a href="#tab_mapping"><?php echo $tab_mapping; ?></a><!-- <a href="#tab_import"><?php echo $tab_import; ?></a> --></div>
      <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="import_form">
	    <div id="tab_mapping">
        <table class="form">
	        <tr class="instructions">
	        	<td colspan="3">The OpenCart field column contains the name of the field in OpenCart, the Feed Field is where you must enter the heading name of the column that you want to import to each OpenCart field. You can set each field to "None" if you do not have anything to import there.
	        	None of the fields are required, but the more you can provide the better your import will be.</td>
	        </tr>
	        <tr><td>Sample From Your Feed</td></tr>
	        <tr>
	        	<td>
					<table class="list">
						<thead>
						<tr>
						<?php foreach ($fields as $field) { ?>
							<td class="center"><?php echo $field; ?></td>
						<?php } ?>
						<tr>
						</thead>
						<tbody><tr>
						<?php foreach ($feed_sample as $key=>$value) { ?>
							<td class="center"><?php $value = strip_tags($value); echo (strlen($value) > 90) ? substr($value, 0, 90) . '...' : $value; ?></td>
						<?php } ?>
						</tr></tbody>
					</table>        	
				</td>
	        </tr>
	        
	      	<!-- mapping fields to names -->
			<tr>
				<td colspan="3"><table>
					<tr><td style="width:200px;"><h2><?php echo $text_field_oc_title; ?></h2></td><td><h2><?php echo $text_field_feed_title; ?></h2></td>
					<?php foreach ($field_map as $input_name => $pretty_name) { ?>
						<?php if (!is_array($pretty_name)) { ?>
						<?php if (in_array($input_name, $multi_language_fields)) { ?>
							<?php foreach ($languages as $lang) { ?>
							<!-- Normal Field (Multi Language) -->
							<tr>
								<td style="width:200px;"><?php echo $pretty_name; ?>&nbsp;<img src="view/image/flags/<?php echo $lang['image']; ?>" title="<?php echo $lang['name']; ?>" /></td>
								<td><select name="field_names[<?php echo $input_name?>][<?php echo $lang['language_id']; ?>]">
									<option value=''>None</option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names[$input_name]) && isset($field_names[$input_name][$lang['language_id']]) && $field_names[$input_name][$lang['language_id']] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							<!-- END Normal Field (Multi Language) -->
							<?php } ?>
						<?php } else { ?>
						<!-- Normal Field -->
						<tr>
							<td style="width:200px;"><?php echo $pretty_name; ?></td>
							<td><select name="field_names[<?php echo $input_name?>]">
								<option value=''>None</option>
								<?php foreach ($fields as $field) { ?>
								<option value="<?php echo $field; ?>" <?php if (isset($field_names) && isset($field_names[$input_name]) && $field_names[$input_name] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
								<?php } ?>
							</select></td>
						</tr>
						<!-- END Normal Field -->
						<?php } ?>
						<?php } elseif ($pretty_name[1] == 'both') { ?>
						<!-- Multi downwards/sideways Field -->
						
							<?php for ($i=0; (isset($field_names[$input_name]) && $i<count($field_names[$input_name]) || !$i && !count($field_names[$input_name])); $i++) { ?>
							<tr class="hori vert">
								<td style="width:200px;"><?php echo $pretty_name[0]; ?><?php if (!isset($field_names[$input_name]) || !count($field_names[$input_name]) || ($i+1) == count($field_names[$input_name])) { ?><a style="float:right;" onclick="return addVert(this, true);" class="button"><span>More&nbsp;&darr;&nbsp;</span></a><?php } ?></td>
								<td>
								<?php for ($j=0; $j<count($field_names[$input_name][$i]) || ($j==0 && !count($field_names[$input_name][$i])); $j++) { ?>
									<select name="field_names[<?php echo $input_name; ?>][<?php echo $i; ?>][]">
										<option value=''>None</option>
										<?php foreach ($fields as $field) { ?>
										<option value="<?php echo $field; ?>" <?php if (isset($field_names) && $field_names[$input_name][$i][$j] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
										<?php } ?>
									</select>
									<?php if (($j+1) == count($field_names[$input_name][$i])) { ?>
									&nbsp;<a onclick="return addSub(this);" class="button"><span>More&nbsp;&rarr;&nbsp;</span></a>
									<?php } ?>
								<?php } ?>
								</td>
							</tr>
							<!-- END Multi downwards/sideways Field -->
							<?php } ?>
						<?php } else { ?>
						<!-- Multi downwards Field -->
						
							<?php for ($i=0; (isset($field_names[$input_name]) && $i<count($field_names[$input_name])) || (!$i && !count($field_names[$input_name])); $i++) { ?>
							<?php if ($i == 0 || (isset($field_names[$input_name]) && $field_names[$input_name][$i])) { ?> 
							<tr class="vert">
								<td style="width:200px;"><?php echo $pretty_name[0]; ?><?php if (!isset($field_names[$input_name]) || !count($field_names[$input_name]) || ($i+1) == count($field_names[$input_name])) { ?><a style="float:right;" onclick="return addVert(this, false);" class="button"><span>More&nbsp;&darr;&nbsp;</span></a><?php } ?></td>
								<td><select name="field_names[<?php echo $input_name; ?>][]">
									<option value=''>None</option>
									<?php foreach ($fields as $field) { ?>
									<option value="<?php echo $field; ?>" <?php if (isset($field_names) && $field_names[$input_name][$i] == $field) echo 'selected="true"'; ?>><?php echo $field; ?></option>
									<?php } ?>
								</select></td>
							</tr>
							<?php } ?>
							<?php } ?>
						<!-- END Multi downwards Field -->
						<?php } ?>
					<?php } ?>
				</table></td>
			</tr>
        </table>
       </div>
      </form>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<?php echo $footer; ?>