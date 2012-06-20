<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################
?>
<?php echo $header; ?>
<script type="text/javascript">
function updateText(el, name) {
	var action = el.value;
	if (name == 'source') {
		$("#file, #url, #filepath, .ftp").hide();
	} else {
		$("#xml, .csv").hide();
	}
	$("#"+action+", ."+action).show();
}

$(document).ready(function() {
	updateText(document.settings_form.source, 'source');
	updateText(document.settings_form.format, 'format');
});
</script>
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
<div class="box">
  <div class="heading">
    <h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#csv_import').submit();" class="button"><span><?php echo $button_next; ?></span></a><a href="<?php echo $skip_url; ?>" class="button"><span><?php echo $button_skip; ?></span></a><a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
  	<div id="tabs" class="htabs"><a href="#tab_fetch">Step 1: Fetch Feed</a><!-- <a href="#tab_adjust"><?php echo $tab_adjust; ?></a><a href="#tab_global"><?php echo $tab_global; ?></a><a href="#tab_mapping"><?php echo $tab_mapping; ?></a><a href="#tab_import"><?php echo $tab_import; ?></a> --></div>
      <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="csv_import">
	    <div id="tab_fetch">
        <table class="form">
	        <!-- Feed Source -->
	        <tr>
	        	<td>Feed Source:</td>
	        	<td colspan="2">
	        		<select name="source" onchange="updateText(this, 'source');">
	        			<option value="file">File Upload</option>
	        			<option value="url" <?php if (isset($source) && $source == 'url') echo 'selected="true"';?>>URL</option>
	        			<option value="ftp" <?php if (isset($source) && $source == 'ftp') echo 'selected="true"';?>>FTP</option>
	        			<option value="filepath" <?php if (isset($source) && $source == 'filepath') echo 'selected="true"';?>>File System</option>
	        		</select>
	        	</td>
	        </tr>
	        <!-- File -->
	        <tr id="file">
	            <td><?php echo $entry_import_file; ?></td>
	            <td colspan="2"><input type="file" name="feed_file" /></td>
	        </tr>
			<!-- ...or URL -->
			<tr id="url">
	            <td><?php echo $entry_import_url; ?></td>
				<td colspan="2"><input type="text" size="70" name="feed_url" value="<?php if (isset($feed_url)) echo $feed_url; ?>" /></td>
	        </tr>
			<!-- ...or FTP -->
			<tr class="ftp">
	            <td><?php echo $entry_ftp_server; ?></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftpserver" value="<?php if (isset($feed_ftpserver)) echo $feed_ftpserver; ?>" /></td>
	        </tr>
			<tr class="ftp">
	            <td><?php echo $entry_ftp_user; ?></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftpuser" value="<?php if (isset($feed_ftpuser)) echo $feed_ftpuser; ?>" /></td>
	        </tr>
			<tr class="ftp">
	            <td><?php echo $entry_ftp_pass; ?></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftppass" value="<?php if (isset($feed_ftppass)) echo $feed_ftppass; ?>" /></td>
	        </tr>
			<tr class="ftp">
	            <td><?php echo $entry_ftp_path; ?></td>
				<td colspan="2"><input type="text" size="70" name="feed_ftppath" value="<?php if (isset($feed_ftppath)) echo $feed_ftppath; ?>" /></td>
	        </tr>
	        <!-- ...or File Path -->
	        <tr id="filepath">
	            <td><?php echo $entry_import_filepath; ?></td>
	            <td colspan="2"><input type="text" name="feed_filepath" /></td>
	        </tr>
	        <!-- Unzip feed -->
	        <tr>
	        	<td>Unzip Feed:</td>
	        	<td colspan="2"><input type="checkbox" name="unzip_feed" <?php if (isset($unzip_feed)) echo 'checked="1" '; ?>/></td>
	        </tr>
	        <!-- Feed Format -->
	        <tr>
	        	<td>Feed Format:</td>
	        	<td colspan="2">
	        		<select name="format" onchange="updateText(this, 'format');">
	        			<option value="csv">CSV</option>
	        			<option value="xml" <?php if (isset($format) && $format == 'xml') echo 'selected="true"'; ?>>XML</option>
	        		</select>
	        	</td>
	        </tr>
	        <!-- (XML Only) Product Tag -->
	        <tr id="xml">
	        	<td><?php echo $entry_xml_product_tag; ?></td>
	        	<td colspan="2"><input type="text" name="xml_product_tag" value="<?php if (isset($xml_product_tag)) echo $xml_product_tag; ?>"></td>
	        </tr>
	      	<!-- (CSV Only) Delimiter -->
			<tr class="csv">
				<td><?php echo $entry_delimiter; ?></td>
				<td colspan="2">
					<select name="delimiter">
						<option value="," <?php if (isset($delimiter) && $delimiter == ',') { echo 'selected="true"'; } ?>>,</option>
						<option value="\t" <?php if (isset($delimiter) && $delimiter == '\t') { echo 'selected="true"'; } ?>>Tab</option>
						<option value="|" <?php if (isset($delimiter) && $delimiter == '|') { echo 'selected="true"'; } ?>>|</option>
						<option value=";" <?php if (isset($delimiter) && $delimiter == ';') { echo 'selected="true"'; } ?>>;</option>
						<option value="^" <?php if (isset($delimiter) && $delimiter == '^') { echo 'selected="true"'; } ?>>^</option>
					</select>
				</td>
			</tr>
			<tr class="csv">
		       	<td>First Row Is Headings:</td>
		       	<td colspan="2"><input type="checkbox" name="has_headers" <?php if (!isset($has_headers) || (isset($has_headers) && $has_headers == 'on')) echo 'checked="1"';?>/></td>
			</tr>
			<tr class="csv">	
				<!-- Use Safe Headers -->
		       	<td>Use Safe Headings: <span class="help">ignore the feed's headings</span></td>
		       	<td colspan="2"><input type="checkbox" name="safe_headers" <?php if (isset($safe_headers)) echo 'checked="1" '; ?>/></td>
		     </tr>
        </table>
       </div>
      </form>
  </div>
</div><script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script>
<?php echo $footer; ?>