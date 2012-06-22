<?php
#####################################################################################
#  Module Total Import PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################

// Heading
$_['heading_title']    	= 'Total Import PRO';

// Tabs
$_['tab_fetch'] 		= 'Step 1: Fetch Feed File';
$_['tab_global'] 		= 'Step 2: Global Settings';
$_['tab_adjust'] 		= 'Step 3: Adjust Data';
$_['tab_mapping'] 		= 'Step 4: Field Mapping';
$_['tab_import'] 		= 'Step 5: Import';

// Text
$_['text_add']     					= 'Add';
$_['text_reset']     				= 'Reset';
$_['text_update']     				= 'Add/Update';
$_['text_adjust_help']     			= 'If you need to adjust any data from your feed before importing it you can use the Operations below on the fields in your database. You can also leave this screen open and use phpmyadmin to directly adjust the database table hj_import and then return here to complete the import.';
$_['text_operation']     			= 'Operation';
$_['text_operation_field_name'] 	= 'Field';
$_['text_operation_data'] 			= 'Data';
$_['text_sample'] 					= 'Feed Sample';
$_['text_select_operation']			= '-- Select operation --';
$_['text_select']					= '-- Select --';
$_['text_operation_type']			= 'Operation Type';
$_['text_operation_desc']			= 'Description';


// Entry
$_['entry_import_file']     		= 'Import Feed File:';
$_['entry_import_url']    	 		= 'Import Feed URL:';
$_['entry_import_ftp'] 				= 'Import Feed FTP:';
$_['entry_ftp_server'] 				= 'FTP Server:';
$_['entry_ftp_user'] 				= 'Username:';
$_['entry_ftp_pass'] 				= 'Password:';
$_['entry_ftp_path'] 				= 'Absolute path to file:';
$_['entry_import_filepath']    		= 'Import Feed Local File Path:';
$_['entry_out_of_stock']  			= 'Out of stock status:';
$_['entry_weight_class']  			= 'Weight Class:';
$_['entry_length_class']  			= 'Length Class:';
$_['entry_customer_group']  		= 'Customer Group:';
$_['entry_tax_class']  				= 'Tax Class:';
$_['entry_subtract_stock']  		= 'Subtract Stock:';
$_['entry_requires_shipping']  		= 'Requires Shipping:';
$_['entry_minimum_quantity'] 	 	= 'Minimum Quantity:';
$_['entry_product_status']  		= 'Product Status:';
$_['entry_language']  				= 'Language:';
$_['entry_ignore_fields'] 			= 'Skip Products Where:';
$_['entry_store']  	   				= 'Store:';
$_['entry_remote_images']  	   		= 'Download Remote Images:';
$_['entry_remote_images_warning']  	= 'Warning: This will probably timeout for product feeds larger than around 500 products.';
$_['entry_language']   				= 'Language:';
$_['entry_xml_product_tag']     	= 'XML Product Tag:';
$_['entry_delimiter']  				= 'CSV Field Delimiter:';
$_['entry_data_feed']  				= 'CSV Data Feed:';
$_['entry_field_mapping']			= 'Field Mapping:';
$_['entry_import_type']				= 'Import Type:';
$_['entry_price_multiplier']		= 'Price Multiplier:';
$_['entry_image_remove']			= 'Image Remove Text:';
$_['entry_image_prepend']			= 'Image Prepend Text:';
$_['entry_image_append']			= 'Image Append Text:';
$_['entry_split_category']			= 'Category Delimiter:';
$_['entry_top_categories']			= 'Add Categories to Top Bar:';
$_['entry_bottom_category_only']	= 'Add Products to:';
$_['entry_new_items'] 				= 'New Items:';
$_['entry_existing_items'] 			= 'Existing Items:';
$_['entry_reset'] 					= 'Empty store before import:';


// Fields
$_['text_field_oc_title']	 		= 'OpenCart Field';
$_['text_field_feed_title']	 		= 'Feed Field';
$_['text_field_name']     			= 'Name';
$_['text_field_redirect']     			= 'Redirect';
$_['text_field_price']     			= 'Price';
$_['text_field_special_price']     	= 'Special Price';
$_['text_field_model']    	 		= 'Model';
$_['text_field_sku']     			= 'Sku';
$_['text_field_upc']     			= 'UPC';
$_['text_field_points']     		= 'Points';
$_['text_field_manufacturer']     	= 'Manufacturer';
$_['text_field_attribute']     		= 'Attribute';
$_['text_field_category']     		= 'Category';
$_['text_field_quantity']     		= 'Quantity';
$_['text_field_image']     			= 'Image';
$_['text_field_additional_image']   = 'Additional Image';
$_['text_field_description']     	= 'Description';
$_['text_field_meta_desc']     		= 'Meta Description';
$_['text_field_meta_keyw']     		= 'Meta Keywords';
$_['text_field_weight']     		= 'Weight';
$_['text_field_length']     		= 'Length';
$_['text_field_height']     		= 'Height';
$_['text_field_width']     			= 'Width';
$_['text_field_location']    	 	= 'Location';
$_['text_field_keyword']     		= 'SEO Keyword';
$_['text_field_tags']     			= 'Product Tags';
$_['text_field_sort_order']			= 'Sort Order';
$_['text_field_option']				= 'Options';
$_['text_field_reward']				= 'Reward Points';
$_['text_field_product_related']	= 'Related Products';
$_['text_field_subtract_stock']		= 'Subtract Stock';
$_['text_field_requires_shipping']	= 'Requires Shipping';
$_['text_field_minimum_quantity']	= 'Minimum Quantity';
$_['text_field_product_status']		= 'Product Status';

// Import
$_['button_fetch']	   			= 'Fetch';
$_['button_import']	   			= 'Import';
$_['button_next']	   			= 'Save &amp; Continue';
$_['button_skip']	   			= 'Skip';
$_['button_save'] 	   			= 'Save';
$_['button_cancel']	   			= 'Cancel';
$_['button_add_operation']	   	= 'Add Adjustment';


// Success
$_['text_success_step1']     	= 'Success: Feed fetched and parsed. Ready for import: %s; Invalid CSV rows: %s';
$_['text_success_step2']     	= 'Success: Global Settings saved.';
$_['text_success_step3']     	= 'Success: Operations saved.';
$_['text_success_step4']     	= 'Success: Mappings saved.';
$_['text_success_step5']     	= 'Success: Added %s products, Updated %s products';

// Error
$_['error_permission'] 			= 'Warning: You do not have permission to modify total import!';
$_['error_empty']      			= 'Warning: No file or empty file!';
$_['error_mac_csv']      		= 'Warning: CSV file contains only one line: If you create your CSV file with Mac you need to save it as "CSV (Windows)".';
$_['error_wrong_delimiter']    	= 'Warning: The delimiter you have chosen does not seem to be correct!';
$_['error_xml_product_tag']		= 'Warning: You must specify a Product Tag for XML files!';
$_['error_no_db'] 				= 'Warning: Step 1 must be completed correctly before proceeding!';

?>