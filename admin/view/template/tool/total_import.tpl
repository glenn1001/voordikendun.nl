<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.1.x From HostJars opencart.hostjars.com #
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
<div class="box">
  <div class="heading">
    <h1><img src='view/image/feed.png' /><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
      <form action="<?php echo $action; ?>" method="post" name="settings_form" enctype="multipart/form-data" id="csv_import">
        <table class="form">
        	<tr class="instructions"><td colspan="2">You can use the buttons below to skip to the steps you require. You will usually need to run at least Step 1 and Step 5. If you are using this module for the first time, you should run all steps in order from Step 1.</td></tr>
	        <tr><td><a target="_blank" href="view/image/UserGuide.pdf"><img src="view/image/pdf-icon.png"></a></td><td>User Guide</td></tr>
	        <?php if (count($saved_settings)) { ?>
	        <tr><td>
	        	<span><label for="settings_groupname">Load Settings Profile:</label>
	        	<select name="settings_groupname">
	        			<?php foreach ($saved_settings as $setting_name) { ?><option value="<?php echo $setting_name; ?>"><?php echo $setting_name; ?></option><?php } ?>
        		</select></span>
        		</td><td>
        		<input type="submit" value="Load" />
	        </td></tr>
	        <?php } ?>
	        <?php foreach ($pages as $page => $page_info) { ?>
	        <tr>
	        	<td style="width:80px;"><a href="<?php echo $page_info['link']; ?>" class="button"><span><?php echo $page_info['button']?></span></a></td>
	        	<td><?php echo $page_info['title']?></td>
	        </tr>
	        <?php } ?>
        </table>
      </form>
  </div>
</div>
<?php echo $footer; ?>