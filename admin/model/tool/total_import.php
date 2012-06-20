<?php
#####################################################################################
#  Module TOTAL IMPORT PRO for Opencart 1.5.x From HostJars opencart.hostjars.com 	#
#####################################################################################

class ModelToolTotalImport extends Model 
{
	private $xml_product;
	private $xml_existing_fields = array();
	private $total_items_added = 0;
	private $total_items_updated = 0;
	private $total_items_missed = 0;	//wrong number of fields in CSV row
	private $total_items_ready = 0;		//in hj_import db ready for store import
	
	public function getManufacturerId($manufacturer_name) {
		$query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($manufacturer_name) . "'");
		return (isset($query->row['manufacturer_id'])) ? $query->row['manufacturer_id'] : 0;
	}
	
	public function getCategoryId($category_name, $parentid) {
		$query = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE (cd.name = '" . $this->db->escape($category_name) . "' OR cd.name = '" . $this->db->escape(htmlentities($category_name)) . "') AND c.parent_id = '" . (int)$parentid . "'");
		return (isset($query->row['category_id'])) ? $query->row['category_id'] : 0;
	}
	
	public function getAttributeId($attribute_name, $attribute_group) {
		$query = $this->db->query("SELECT a.attribute_id FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.name = '" . $this->db->escape($attribute_name) . "' AND a.attribute_group_id = '" . (int)$attribute_group . "'");
		return (isset($query->row['attribute_id'])) ? $query->row['attribute_id'] : 0;
	}
	
	public function getAttributeGroupId($attribute_name) {
		$query = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group_description WHERE name = '" . $this->db->escape($attribute_name) . "'");
		return (isset($query->row['attribute_group_id'])) ? $query->row['attribute_group_id'] : 0;
	}
	
	public function getProductId($id_field, $id_value) {
		if ($id_field == 'name') {
			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_description WHERE name = '" . $this->db->escape($id_value) . "'");
		} else {
			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE " . $this->db->escape($id_field) . " = '" . $this->db->escape($id_value) . "'");
		}
		return (isset($query->row['product_id'])) ?	$query->row['product_id'] : 0;
	}
	
	public function getOptions($options) {
		$all_values = array();
		foreach ($options as $option) {
			if ($option) {
				$sql = 'SELECT `' . $option . '` FROM ' . DB_PREFIX . 'hj_import';
				$query = $this->db->query($sql);
				$values = array();
				foreach ($query->rows as $row) {
					$opt_values = explode('|', $row[$option]);
					foreach ($opt_values as $opt_value) {
						$opt_value_details = explode(':', $opt_value);
						if (count($opt_value_details) == 5) { //we have a sort order
							$values[] = $opt_value_details[0] . '|' . $opt_value_details[4];
						} else {
							$values[] = $opt_value_details[0];
						}
					}
				}
				$all_values[$option] = array_unique($values);
			}
		}
		return $all_values;
	}
	
	public function getOptionIdByName($name) {
		$query = $this->db->query("SELECT option_id FROM " . DB_PREFIX . "option_description WHERE name='" . $this->db->escape($name) . "'");
		return (count($query->rows)) ? $query->rows[0]['option_id'] : 0;
	}

	public function getOptionValueIdByName($name, $option_id) {
		$query = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE name='" . $this->db->escape($name) . "' AND option_id='" . (int)$option_id . "'");
		return (count($query->rows)) ? $query->rows[0]['option_value_id'] : 0;
	}

	public function emptyTables() {
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_attribute");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_group");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "attribute_group_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_discount");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_image");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_option");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_option_value");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_related");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_reward");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_special");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_tag");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_category");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_download");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_layout");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_to_store");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "manufacturer");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "manufacturer_to_store");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_description");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_to_layout");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "category_to_store");
		$query = $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "review");
		
		// Special query to delete any product related SEO Keywords
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'product_id=%'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'category_id=%'");
		$query = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query LIKE 'manufacturer_id=%'");
		
		$this->cache->delete('product');
		
	}

	//Functions to adjust data on the way in:
	
	public function getOperations($hide_func=true) {
		$operations = array(
			'append' => array(
           		'name' => 'Append Text',
           		'function' => ($hide_func) ? '' : "appendText",
		    	'inputs'=>array(
					array('type'=>'text', 'prepend'=>'Append'),
					array('type'=>'field', 'prepend'=>'after')
				),
			),
	        'prepend' => array( 
           		'name' => 'Prepend Text',
           		'function' => ($hide_func) ? '' : 'prependText',
           		'inputs'=>array(
					array('type'=>'text', 'prepend'=>'Prepend'),
					array('type'=>'field', 'prepend'=>'to')
				),
			),
			'replace' => array( 
		        'name'=> 'Replace Text',
           		'function' => ($hide_func) ? '' : 'replaceText',
		        'inputs'=>array(
					array('type'=>'text', 'prepend'=>'Replace'),
					array('type'=>'text', 'prepend'=>'with'),
					array('type'=>'field', 'prepend'=>'in')
				),
			),
			'remove' => array( 
		        'name'=> 'Remove Text',
           		'function' => ($hide_func) ? '' : 'removeText',
		        'inputs'=>array(
					array('type'=>'text', 'prepend'=>'Remove'),
					array('type'=>'field', 'prepend'=>'in')
				),
			),
			'multiply' => array(
           		'name' => 'Multiply',
           		'function' => ($hide_func) ? '' : 'multiply', 
           		'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Multipy'),
					array('type'=>'text', 'prepend'=>'by')
				),
			),
			'add' => array(
				'name' => 'Add',
				'function' => ($hide_func) ? '' : 'add', 
				'inputs'=>array(
					array('type'=>'text', 'prepend'=>'Add'),
					array('type'=>'field', 'prepend'=>'to')
				),
			),
			'deleteRow' => array(
           		'name' => 'Filter Products (containing)',
           		'function' => ($hide_func) ? '' : 'deleteRowsWhere', 
           		'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Exclude products where'),
					array('type'=>'text', 'prepend'=>'equals')
				),
			),
			'deleteRowWhereNot' => array(
           		'name' => 'Filter Products (not containing)',
           		'function' => ($hide_func) ? '' : 'deleteRowsWhereNot', 
           		'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Exclude products where'),
					array('type'=>'text', 'prepend'=>'does not equal')
				),
			),
			'duplicateField' => array(
			    'name' => 'Duplicate Field',
			   	'function' => ($hide_func) ? '' : 'duplicateField', 
			    'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Duplicate'),
					array('type'=>'text', 'prepend'=>'to')
				),
			),
			'mergeColumns' => array(
			    'name' => 'Append Field to Field',
			   	'function' => ($hide_func) ? '' : 'mergeColumns', 
			    'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Append'),
					array('type'=>'field', 'prepend'=>'to'),
					array('type'=>'text', 'prepend'=>'separated by')
				),
			),
			/*'mergeRows' => array(
			    'name' => 'Merge Rows',
			   	'function' => ($hide_func) ? '' : 'mergeRows', 
			    'inputs'=>array(
					array('type'=>'field', 'prepend'=>'Merge'),
					array('type'=>'field', 'prepend'=>'over multiple rows matching')
				),
			),*/
		);
		return $operations;
	}
	
	public function runAdjustments(&$adjustments) {
		$operations = $this->getOperations(false);
		foreach ($adjustments as $adjustment) {
			$op_name = array_shift($adjustment);
			if (is_callable(array($this, $operations[$op_name]['function']))) {
				if (!in_array('-- Select --', $adjustment)) {call_user_func_array(array($this, $operations[$op_name]['function']), $adjustment);}
			}
		}
	}
	
	
	/**
	 * @param (mixed) array(text to append to, field to adjust)
	 */
	public function appendText($append_text, $field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" .$field . "` = CONCAT( `" . $field . "`, '" . $this->db->escape($append_text) . "' )");
	}
	
	/**
	 * @param (mixed) array(text to prepend to, field to adjust)
	 */
	public function prependText($prepend_text, $field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = CONCAT( '" . $this->db->escape($prepend_text) . "', `" . $field . "` )");
	}
	
	/**
	 * @param (mixed) array(text to remove, field to adjust)
	 */
	public function removeText($remove_text, $field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" .$field . "` = REPLACE( `" . $field . "`, '" . $this->db->escape($remove_text) . "', '' )");
	}
	
	/**
	 * @param (mixed) array(text to find, text to replace with, field to adjust)
	 */
	public function replaceText($str, $replacement, $field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = REPLACE( `" . $field . "`, '" . $this->db->escape($str) . "', '" . $this->db->escape($replacement) . "' )");
	}
	
	/**
	* @param (mixed) array(field to adjust, multiplication factor)
	*/
	public function multiply($field, $multiplier) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = (`" . $field . "` * " . (float)$multiplier . " )");
	}
	
	/**
	* @param (mixed) array(field to  add value, adjust)
	*/
	public function add($add, $field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = (`" . $field . "` + " . (float)$add . " )");
	}
	
	/**
	* @param (mixed) array(field to  adjust, new field)
	*/
	public function duplicateField($field, $newfield) {
		$this->db->query('ALTER TABLE ' . DB_PREFIX . "hj_import ADD `" . $newfield . "` BLOB");
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $newfield . "` = (`" . $field . "`)");
	}
	
	/**
	* @param (mixed) array(field to adjust)
	*/
	public function lowerCase($field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = LCASE( `" . $field . "` )");
	}
	
	/**
	* @param (mixed) array(field to adjust)
	*/
	public function upperCase($field) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field . "` = UCASE( " . $field . " )");
	}

	/**
	* @param (mixed) array(field to adjust)
	*/
//	public function capitalize(&$adjust) {
//		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $adjust[0] . "` = CAP_FIRST( '" . $adjust[0] . "' )");
//	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhere($field, $value) {
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` = '" . $this->db->escape($value) . "'");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function deleteRowsWhereNot($field, $value) {
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import WHERE `" . $field . "` != '" . $this->db->escape($value) . "'");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function mergeColumns($field1, $field2, $separator) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field2 . "` = CONCAT(`" . $field2 . "`, '" . $separator . "', `" . $field1 . "`)");
	}
	
	/**
	* @param (mixed) array(field to adjust, text to look for)
	*/
	public function mergeRows($unique_id, $field_to_merge) {
		$this->db->query('UPDATE ' . DB_PREFIX . "hj_import SET `" . $field1 . "` = CONCAT(`" . $field1 . "`, '" . $separator . "', `" . $field2 . "`)");
	}
	
	//Internal database table functions:
	
	public function createEmptyTable($headings) {
		$this->db->query('DROP TABLE IF EXISTS ' . DB_PREFIX . 'hj_import');
		$sql = 'CREATE TABLE ' . DB_PREFIX . 'hj_import (hj_id INT(11) AUTO_INCREMENT, ';
		foreach ($headings as $heading) {
			$sql .= "`" . $heading . "` BLOB, ";
		}
		$sql .= 'PRIMARY KEY (hj_id))';
		$query = $this->db->query($sql);
	}
	
	public function alterImportTable($new_fields) {
		if (!empty($new_fields)) {
			$sql = "ALTER TABLE " . DB_PREFIX . "hj_import ADD COLUMN ";
			$fields_sql = array();
			foreach ($new_fields as $field) {
				$fields_sql[] = '`' . $field . '` BLOB';
			}
			$sql .= '(' . implode(', ', $fields_sql) . ')';
			$this->db->query($sql);
		}
	}
	
	public function dbReady() {
		$query = $this->db->query("SHOW TABLES WHERE `Tables_in_" . DB_DATABASE . "` = '" . DB_PREFIX . "hj_import'");
		return ($query->num_rows == 1);
	}
	
	public function insertProduct($product) {
		$sql = 'INSERT INTO ' . DB_PREFIX . 'hj_import SET ';
		$values = array();
		foreach ($product as $key => $value) {
			$values[] = '`' . $key . "`='" . $this->db->escape($value) . "'";
		}
		$sql .= implode(',', $values);
		$query = $this->db->query($sql);
	}
	
	public function getNextProduct($start=0) {
		$query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'hj_import LIMIT ' . (int)$start . ', 1');
		return $query->row;
	}
	
	public function getSavedSettingNames() {
		//create db if doesn't exist.
		$sql = 'CREATE TABLE IF NOT EXISTS ' . DB_PREFIX . 'hj_import_settings (`id` INT(11) AUTO_INCREMENT, `group` VARCHAR(255), `step` INT(11), `name` BLOB, `value` BLOB, PRIMARY KEY (id))';
		$this->db->query($sql);
		$query = $this->db->query("SELECT DISTINCT(`group`) FROM " . DB_PREFIX . "hj_import_settings");
		$names = array();
		foreach ($query->rows as $row) {
			$names[] = $row['group'];
		}
		return $names;
	}
	
	public function saveSettings($name) {
		
		$this->load->model('setting/setting');
		$settings = array(
			$this->model_setting_setting->getSetting('import_step1'),
			$this->model_setting_setting->getSetting('import_step2'),
			$this->model_setting_setting->getSetting('import_step3'),
			$this->model_setting_setting->getSetting('import_step4'),
			$this->model_setting_setting->getSetting('import_step5')
		);
		
		//get settings from step1-5 and save them with a name in $data
		$this->db->query('DELETE FROM ' . DB_PREFIX . "hj_import_settings WHERE `group` = '" . $this->db->escape($name) . "'");
		for ($i=0; $i<count($settings); $i++) {
			foreach ($settings[$i] as $key => $value) {
				$this->db->query('INSERT INTO ' . DB_PREFIX . "hj_import_settings SET `group` = '" . $this->db->escape($name) . "', `step` = '" . (int)($i+1) . "', `name` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'"); 
			}
		}
	}
	
	public function loadSettings($name) {
		$this->load->model('setting/setting');
		for ($i=1; $i<=5; $i++) {
			$settings = array(); 
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "hj_import_settings WHERE `group` = '" . $this->db->escape($name) . "' AND `step` = " . $i);
			foreach ($query->rows as $result) {
				$settings[$result['name']] = $result['value'];
			}
			$this->model_setting_setting->editSetting('import_step' . $i, $settings);
		}
	}
	
	
	public function fetchFeed(&$settings, $unzip_feed=false) {
		$success = false;
		$filename = DIR_APPLICATION . 'feed.txt';
		if ($settings['source'] == 'file') {
			if (defined('CLI_INITIATED')) {
				$success = true; //we will do it with whatever feed is on the filesystem, no need to fetch.
			} elseif (is_uploaded_file($this->request->files['feed_file']['tmp_name'])) {
				$success = move_uploaded_file($this->request->files['feed_file']['tmp_name'], $filename);
			}
		} elseif ($settings['source'] == 'url') {
			$ch = curl_init();
			$fp = fopen($filename, 'w');
			$url = str_replace('&amp;', '&', $settings['feed_url']);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			$success = ($httpCode != '404');
		} elseif ($settings['source'] == 'ftp') {
			$success = $this->fetchFtp($settings['feed_ftpserver'], $settings['feed_ftpuser'], $settings['feed_ftppass'], $settings['feed_ftppath'], $filename);
		} elseif ($settings['source'] == 'filepath') {
			rename($settings['feed_filepath'], $filename);
		} elseif ($settings['source'] == 'db') {
			//@todo implement direct from DB.
		}
	
		if ($unzip_feed) {
			$temp_file = $this->unzip($filename);
			rename($temp_file, $filename);
		}
		
		return ($success) ? $filename : '';
	}
	
	private function fetchFtp($server, $user, $pass, $remote_file, $local_file) {
		$conn_id = ftp_connect($server);
		$login_result = ftp_login($conn_id, $user, $pass);
		$success = ftp_get($conn_id, $local_file, $remote_file, FTP_BINARY);
		ftp_close($conn_id);
		return $success;
	}
	/*
	 * 
	 * function fetchImage
	 * 
	 * @desc - fetches an image from a URL. If the URL contains a ? character the new image's filename
	 * will be the md5 of the full URL. If not, it will be the last portion of the URL (after the last /).
	 * If the image URL returns a 404 the image will be deleted and an empty string returned.
	 * 
	 * @param (string) the image url to fetch
	 * @return (string) the name of the fetched file on disk relative to the image/ dir or empty string on 404
	 * 
	 */
	public function fetchImage($image_url) {
		if (strpos($image_url, 'http') !== 0) {
			return '';
		}
		if (strstr($image_url, '?')) {
			$filename = 'data/' . md5($image_url) . '.jpg';
		} else {
			$url_parts = explode('/', $image_url);
			$filename = 'data/' . end($url_parts);
		}
		if (!file_exists(DIR_IMAGE . $filename)) {
			$fp = fopen(DIR_IMAGE . $filename, 'w');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $image_url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			$file_info = getimagesize(DIR_IMAGE . $filename);
			if($httpCode == 404 || empty($file_info) || strpos($file_info['mime'], 'image/') !== 0) {
				unlink(DIR_IMAGE . $filename);
				$filename = '';
			}
		}
		return $filename;
	}
	
	
	public function importFile($filename, &$settings) {
		if ($settings['format'] == 'csv') {
			if ($settings['delimiter'] == '\t' ) {
				$settings['delimiter'] = "\t";
			} elseif ($settings['delimiter'] == '') {
				$settings['delimiter'] = ',';
			}
			$csv_options = array();
			if(!empty($settings['safe_headers'])) {
				$csv_options['safe_headers'] = $settings['safe_headers'];
			}
			if(!empty($settings['has_headers'])) {
				$csv_options['has_headers'] = $settings['has_headers'];
			}
			$this->importCSV($filename, $settings['delimiter'], $csv_options);
		} elseif ($settings['format'] == 'xml') {
			$this->table_created = false;
			$this->importXML($filename, $settings['xml_product_tag']);
		}
		return array('total_items_ready'=>$this->total_items_ready, 'total_items_missed'=>$this->total_items_missed);
	}
	
	private function importXML($filename, $product_tag) {
		$this->product_tag = $product_tag;
		$this->xml_data = '';
		$fh = fopen($filename, 'r');
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, 'startTag', 'endTag');
		xml_set_character_data_handler($xml_parser, 'cData');
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		while ($data = fread($fh, 4096)) {
			if (!xml_parse($xml_parser, $data, feof($fh))) {
				//@todo Handle XML format error gracefully.
				die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
			}
		}
		xml_parser_free($xml_parser);
	}
	
	private function importCSV($filename, $delimiter, $csv_options) {
		$fh = fopen($filename, 'r');
		if(!empty($csv_options['safe_headers']) || empty($csv_options['has_headers'])) {
			$count = count(fgetcsv($fh, 0, $delimiter));
			//if there are no file headers, reset the file read after doing the count
			if(empty($csv_options['has_headers'])) {
				$fh = fopen($filename, 'r');
			}
			for($i = 0; $i < $count; $i++) {
				$headings[$i] = 'column_' . $i;
			}
		}
		else
		{
			$headings = array_map('trim', fgetcsv($fh, 0, $delimiter)); //trim white space from all headings for db insertion.
		}
		
		$column_number = 1;
		foreach ($headings as $key=>$value)
		{
			if(empty($value)) {
				$headings[$key] = ' column_' . $column_number;
				$column_number++;
			}
		}
		
		$this->createEmptyTable($headings);
		$num_cols = count($headings);
		//most complicated do-while ever written.
		do {
			//miss items that have incorrect column count:
			while (($row = fgetcsv($fh, 0, $delimiter)) !== FALSE && count($row) != $num_cols) {
				$this->total_items_missed++;
			}
			if ($row) {
				$this->insertProduct(array_combine($headings, $row));
				$this->total_items_ready++;
			}
		} while ($row);
	}
	
	
	
	private function unzip($file) {
		$filename = $file;
		$zip = zip_open($file);
		if (is_resource($zip)) {
			$zip_entry = zip_read($zip);
			$filename = zip_entry_name($zip_entry);
			$fp = fopen($filename, 'w');
			if (zip_entry_open($zip, $zip_entry, 'r')) {
				$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				fwrite($fp,"$buf");
				zip_entry_close($zip_entry);
				fclose($fp);
			}
			zip_close($zip);
		}
		return $filename;
	}

	/*
	*
	* XML parser support functions:
	*
	* startTag
	* endTag
	* cData
	*
	*/
	private function startTag ($parser, $name, $attr) {
		if (strcmp($name, $this->product_tag) == 0) {
			$this->xml_product = array();
		}
		//Get attributes
		foreach ($attr as $key=>$value) {
			if (!isset($this->xml_product[$name.'_attr_'.$key])) {
				$this->xml_product[$name.'_attr_'.$key] = $value;
			} else {
				$this->xml_product[$name.'_attr_'.$key] .= '^' . $value;
			}
		}
	}
	
	private function endTag ($parser, $name) {
		if (strcmp($name, $this->product_tag) == 0) {
			if (!$this->table_created) {
				$this->createEmptyTable(array_keys($this->xml_product));
				$this->xml_existing_fields = array_keys($this->xml_product);
				$this->table_created = true;
			}
			$new_columns = array_diff(array_keys($this->xml_product), $this->xml_existing_fields);
			if (!empty($new_columns)) {
				$this->alterImportTable($new_columns);
				$this->xml_existing_fields = array_unique(array_merge($this->xml_existing_fields, $new_columns));
			}
			$this->insertProduct($this->xml_product);
			$this->total_items_ready++;
		} else {
			if (isset($this->xml_product[$name])) {
				$this->xml_product[$name] .= '^' . $this->xml_data;
			} else {
				$this->xml_product[$name] = $this->xml_data;
			}
		}
		$this->xml_data = '';
	
	}
	
	private function cData($parser, $content) {
		$this->xml_data .= trim($content);
	}
	
}


?>