<?php
#########################################################################################
#  Tests for TOTAL IMPORT PRO for Opencart 1.5.1.3 From HostJars opencart.hostjars.com 	#
#########################################################################################
/*
 * 
 * Test Suite for TOTAL IMPORT PRO module, OC1.5.1.3
 * All tests must be run from an empty store, so this will reset the store first.
 * 
 */
class ModelToolTotalImportTest extends Model 
{
	
	public function getAddTests() {
		return array(
			$this->testAddSkeleton(),
			$this->testAddBasic(),
			$this->testAddCategory(),
			$this->testAddManufacturer(),
			$this->testAddAdditionalImage(),
		);
	}
	
	public function getUpdateTests() {
		return array(
// 			$this->testUpdateBasic(),
// 			$this->testUpdateCategory(),
// 			$this->testUpdateManufacturer(),
// 			$this->testUpdateAdditionalImage(),
		);
	}
	
	public function getImageFetchTests() {
		$tests = array();
		$tests[] = array(
			'description' => 'HTML page should not generate local file',
			'input' => 'http://www.google.com',
			'expected' => ''
		);
		$tests[] = array(
			'description' => 'Actual image should fetch and name it as last part of URL',
			'input' => 'http://cdn.hostjars.com/image/cache/data/product/CategoryAccordion-94x90.jpg',
			'expected' => 'data/CategoryAccordion-94x90.jpg'
		);
		$tests[] = array(
			'description' => 'Image fetch using URL with parameters should receive a random md5 name',
			'input' => 'http://cdn.hostjars.com/image/cache/data/product/CategoryAccordion-94x90.jpg?id=1234',
			'expected' => 'data/' . md5('http://cdn.hostjars.com/image/cache/data/product/CategoryAccordion-94x90.jpg?id=1234') . '.jpg'
		);
		$tests[] = array(
			'description' => '404 should not create local file',
			'input' => 'http://opencart.hostjars.com/sgaakdglhsdfadf',
			'expected' => ''
		);
		$tests[] = array(
			'description' => 'Non-URL should generate no file',
			'input' => '/var/www/image.jpg',
			'expected' => ''
		);
		return $tests;
	}
	
	private function testAddAdditionalImage() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['manufacturer'] = 'manu';
		$settings['field_names']['category'] = array(array('cat'));
		$settings['field_names']['product_image'] = array('image1', 'image2');
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1001';
		$expected['manufacturer_id'] = '1';
		$expected['product_category'] = array(1);
		$expected['product_image'] = array(
			array('image' => 'data/img1.jpg', 'sort_order' => ''), 
			array('image' => 'data/img2.jpg', 'sort_order' => '')
		);
		$test = array(
			'name' => 'Add Additional Images',
			'update_id' => 0,
			'settings' => $settings,
			'input'=> array(
				'name'=>'test 1',
				'price'=>'2.50',
				'desc'=>'a test product',
				'manu'=>'Sony',
				'cat'=>'Test Cat',
				'model'=>'1001',
				'image1'=>'data/img1.jpg',
				'image2'=>'data/img2.jpg',
			),
			'expected' => $expected
		);
		return $test;
	}
	
	private function testAddManufacturer() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['manufacturer'] = 'manu';
		$settings['field_names']['category'] = array(array('cat'));
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1002';
		$expected['manufacturer_id'] = '1';
		$expected['product_category'] = array(1);
		$test = array(
				'name' => 'Add Manufacturer and use existing Category',
				'update_id' => 0,
				'settings' => $settings,
				'input'=> array(
					'name'=>'test 1',
					'price'=>'2.50',
					'desc'=>'a test product',
					'manu'=>'Sony',
					'cat'=>'Test Cat',
					'model'=>'1002'
		),
				'expected' => $expected
		);
		return $test;
	}
	
	private function testAddCategory() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['category'] = array(array('cat'));
		
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1003';
		$expected['product_category'] = array(1);
		$test = array(
			'name' => 'Add Category',
			'update_id' => 0,
			'settings' => $settings,
			'input'=> array(
				'name'=>'test 1',
				'price'=>'2.50',
				'desc'=>'a test product',
				'manu'=>'Sony',
				'cat'=>'Test Cat',
				'model'=>'1003'
			),
			'expected' => $expected
		);
		return $test;
	}
	
	private function testAddBasic() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1004';
		$test = array(
			'name' => 'Add Name, Price, Model, Description',
			'update_id' => 0,
			'settings' => $settings,
			'input'=> array(
				'name'=>'test 1',
				'price'=>'2.50',
				'desc'=>'a test product',
				'manu'=>'Sony',
				'cat'=>'Test Cat',
				'model'=>'1004'
			),
			'expected' => $expected
		);
		return $test;
	}
	
	private function testAddSkeleton() {
		$settings = $this->skeletonSettings();
		$expected = $this->skeletonAdd($settings);
		$test = array(
			'name' => 'Add Nothing (skeleton test)',
			'update_id' => 0,
			'settings' => $settings,
			'input'=> array(
				//none
			),
			'expected' => $expected
		);
		return $test;
	}
	
	/*
	 * 
	 * These all require actual products to be present in db.
	 * 
	 */	
	private function testUpdateAdditionalImage() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['manufacturer'] = 'manu';
		$settings['field_names']['category'] = array(array('cat'));
		$settings['field_names']['product_image'] = array('image1', 'image2');
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1001';
		$expected['manufacturer_id'] = '1';
		$expected['product_category'] = array(1);
		$expected['product_image'] = array(
		array('image' => 'data/img1.jpg', 'sort_order' => ''),
		array('image' => 'data/img2.jpg', 'sort_order' => '')
		);
		$test = array(
				'name' => 'Add Additional Images',
				'update_id' => 0,
				'settings' => $settings,
				'input'=> array(
					'name'=>'test 1',
					'price'=>'2.50',
					'desc'=>'a test product',
					'manu'=>'Sony',
					'cat'=>'Test Cat',
					'model'=>'1001',
					'image1'=>'data/img1.jpg',
					'image2'=>'data/img2.jpg',
		),
				'expected' => $expected
		);
		return $test;
	}
	
	private function testUpdateManufacturer() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['manufacturer'] = 'manu';
		$settings['field_names']['category'] = array(array('cat'));
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1002';
		$expected['manufacturer_id'] = '1';
		$expected['product_category'] = array(1);
		$test = array(
					'name' => 'Add Manufacturer and use existing Category',
					'update_id' => 0,
					'settings' => $settings,
					'input'=> array(
						'name'=>'test 1',
						'price'=>'2.50',
						'desc'=>'a test product',
						'manu'=>'Sony',
						'cat'=>'Test Cat',
						'model'=>'1002'
		),
					'expected' => $expected
		);
		return $test;
	}
	
	private function testUpdateCategory() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
		$settings['field_names']['category'] = array(array('cat'));
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1003';
		$expected['product_category'] = array(1);
		$test = array(
				'name' => 'Add Category',
				'update_id' => 0,
				'settings' => $settings,
				'input'=> array(
					'name'=>'test 1',
					'price'=>'2.50',
					'desc'=>'a test product',
					'manu'=>'Sony',
					'cat'=>'Test Cat',
					'model'=>'1003'
		),
				'expected' => $expected
		);
		return $test;
	}
	

	private function testUpdateBasic() {
		$settings = $this->skeletonSettings();
		$settings['field_names']['name'] = 'name';
		$settings['field_names']['price'] = 'price';
		$settings['field_names']['model'] = 'model';
		$settings['field_names']['description'] = 'desc';
	
		$expected = $this->skeletonAdd($settings);
		$expected['price'] = '2.50';
		$expected['product_description'][1]['name'] = 'test 1';
		$expected['product_description'][1]['description'] = 'a test product';
		$expected['model'] = '1004';
		$test = array(
				'name' => 'Update Price',
				'update_id' => 1,
				'settings' => $settings,
				'input'=> array(
					'name'=>'test 1',
					'price'=>'2.50',
					'desc'=>'a test product',
					'manu'=>'Sony',
					'cat'=>'Test Cat',
					'model'=>'1004'
		),
				'expected' => $expected
		);
		return $test;
	}

	
	/*
	 * 
	 * We use this to get a bare minimum settings array, overwriting the fields that we care about before returning it.
	 * 
	 */
	private function skeletonSettings() {
		return array (
		    'language' => array(1),
		    'store' => array(0),
		    'remote_images' => 0,
		    'split_category' => '',
		    'bottom_category_only' => 0,
		    'top_categories' => 0,
		    'tax_class' => 9,
		    'length_class' => 1,
		    'weight_class' => 1,
		    'product_status' => 1,
		    'minimum_quantity' => 1,
		    'requires_shipping' => 1,
		    'subtract_stock' => 1,
		    'out_of_stock_status' => 5,
		    'field_names' => array (
	            'name' => '',
	            'price' => '',
	            'product_special' => '',
	            'model' => '',
	            'sku' => '',
	            'description' => '',
	            'manufacturer' => '',
	            'category' => array(array()),
	            'image' => '',
	            'product_image' => array(),
	            'upc' => '',
	            'quantity' => '',
	            'weight' => '',
	            'length' => '',
	            'height' => '',
	            'width' => '',
	            'location' => '',
	            'keyword' => '',
	            'product_tag' => '',
	            'points' => '',
	            'product_reward' => '',
	            'meta_description' => '',
	            'meta_keyword' => '',
	            'product_option' => array(),
	            'product_attribute' => array()
        	)
		);
	}
	
	private function skeletonAdd(&$settings) {
		return array (
			'product_description' => array (
				'1' => array (
					'name' => 'No Title',
					'meta_description' => '',
					'meta_keyword' => '',
					'description' => ''
				)
			),
			'model' => '',
			'sku' => '',
			'upc' =>'',
			'location' =>'',
			'price' =>'',
			'tax_class_id' => $settings['tax_class'],
			'quantity' => 1,
			'minimum' => $settings['minimum_quantity'],
			'maximum' => 0,
			'subtract' => $settings['subtract_stock'],
			'stock_status_id' => $settings['out_of_stock_status'],
			'shipping' => $settings['requires_shipping'],
			'keyword' =>'',
			'image' =>'',
			'date_available' => date('Y-m-d', time()-86400),
			'length' =>'',
			'width' =>'',
			'height' =>'',
			'length_class_id' => $settings['length_class'],
			'weight' =>'',
			'weight_class_id' => $settings['weight_class'],
			'status' => $settings['product_status'],
			'sort_order' => 1,
			'manufacturer_id' => 0,
			'product_store' => $settings['store'],
			'product_category' => array(0),
			'points' => 0,
			'product_tag' => array(),
			/*
			'related' => '',
			'option' => '',
			'product_reward' => array (
				'8' => array (
					'points' => ''
				)
				'6' => array (
					'points' => ''
				)
			)
			'product_layout' => array (
				'0' => array (
					'layout_id' => '',
				)
			)*/
		);
	}

	/* 
	 * Recursively diffs two associative arrays.
	 * For testing expected vs actual
	 */
	public function array_rdiff(&$aArray1, &$aArray2) {
		$aReturn = array();
	
		foreach ($aArray1 as $mKey => $mValue) {
			if (array_key_exists($mKey, $aArray2)) {
				if (is_array($mValue)) {
					$aRecursiveDiff = $this->array_rdiff($mValue, $aArray2[$mKey]);
					if (count($aRecursiveDiff)) {
						$aReturn[$mKey] = $aRecursiveDiff;
					}
				} else {
					if ($mValue != $aArray2[$mKey]) {
						$aReturn[$mKey] = $mValue;
					}
				}
			} else {
				$aReturn[$mKey] = $mValue;
			}
		}
		return $aReturn;
	}

}
?>