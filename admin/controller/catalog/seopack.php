<?php 
class ControllerCatalogSeoPack extends Controller { 
	private $error = array();
 
	public function index() {
	
		$this->load->language('catalog/seopack');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('seopack', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['action'] = $this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['clearmetas'] = $this->url->link('catalog/seopack/clearmetas', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clearkeywords'] = $this->url->link('catalog/seopack/clearkeywords', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cleartags'] = $this->url->link('catalog/seopack/cleartags', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clearproducts'] = $this->url->link('catalog/seopack/clearproducts', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clearurls'] = $this->url->link('catalog/seopack/clearurls', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['friendlyurls'] = $this->url->link('catalog/seopack/friendlyurls', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['seourls'] = $this->url->link('catalog/seopack/seourls', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "product_description WHERE field = 'custom_title'");

		$exists = 0;
		foreach ($query->rows as $index) {$exists++;}

		if (!$exists) {$this->db->query("ALTER TABLE " . DB_PREFIX . "product_description ADD COLUMN `custom_title` varchar(255) NULL DEFAULT '';");}
		
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "category_description WHERE field = 'custom_title'");

		$exists = 0;
		foreach ($query->rows as $index) {$exists++;}

		if (!$exists) {$this->db->query("ALTER TABLE " . DB_PREFIX . "category_description ADD COLUMN `custom_title` varchar(255) NULL DEFAULT '';");}
		
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "information_description WHERE field = 'custom_title'");

		$exists = 0;
		foreach ($query->rows as $index) {$exists++;}

		if (!$exists) {$this->db->query("ALTER TABLE " . DB_PREFIX . "information_description ADD COLUMN `custom_title` varchar(255) NULL DEFAULT '';");}
		
		$this->data['parameters'] = array();
		
		if (isset($this->request->post['parameters'])) {
			$this->data['parameters'] = $this->request->post['parameters'];
		} elseif ($this->config->get('parameters')) { 
			$this->data['parameters'] = $this->config->get('parameters');
		}
		$initial_parameters = array('parameters'=>array('keywords'=>'%p%c','metas'=>'%p - %f','tags'=>'%p%c','related'=>'5', 'ext'=>'.html'));
		if (!$this->data['parameters']) 
			{
			$this->model_setting_setting->editSetting('seopack', $initial_parameters);		
			$this->data['parameters']  = $initial_parameters['parameters'];			
			}
		
				
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	
	$this->template = 'catalog/seopack.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
	$this->response->setOutput($this->render());
	
		 
	}
	
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/seopack')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function clearmetas() {
	
		$query = $this->db->query("update " . DB_PREFIX . "product_description set meta_description = '';");
		
		$this->session->data['success'] = "Meta descriptions were deleted.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	public function clearkeywords() {
	
		$query = $this->db->query("update " . DB_PREFIX . "product_description set meta_keyword = '';");
		
		$this->session->data['success'] = "Meta keywords were deleted.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	public function cleartags() {
	
		$query = $this->db->query("delete from " . DB_PREFIX . "product_tag;");
		
		$this->session->data['success'] = "Product tags were deleted.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	public function clearproducts() {
	
		$query = $this->db->query("delete from " . DB_PREFIX . "product_related;");
		
		$this->session->data['success'] = "Related products were deleted.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	public function clearurls() {
	
		$query = $this->db->query("delete from " . DB_PREFIX . "url_alias;");
		
		$this->session->data['success'] = "SEO URLs were deleted.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	
	public function friendlyurls() {
	
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'common/home'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'common/home', '')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/wishlist'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/wishlist', 'wishlist')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'information/contact'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'information/contact', 'contact')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/account'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/account', 'account')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'checkout/cart'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'checkout/cart', 'cart')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'checkout/checkout'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'checkout/checkout', 'checkout')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'information/sitemap'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'information/sitemap', 'sitemap')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'product/manufacturer'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'product/manufacturer', 'manufacturer')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'checkout/voucher'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'checkout/voucher', 'voucher')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/voucher'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/voucher', 'gift-voucher')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/account'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/account', 'affiliates')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'product/special'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'product/special', 'special')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/login'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/login', 'login')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/logout'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/logout', 'logout')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/order'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/order', 'order')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/newsletter'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/newsletter', 'newsletter')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/return/insert'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/return/insert', 'my-returns')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/register'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/register', 'register')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/forgotten'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/forgotten', 'forgotten')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/download'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/download', 'downloads')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/return'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/return', 'returns')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/transaction'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/transaction', 'transaction')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/edit'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/edit', 'edit-affiliate-account')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/password'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/password', 'change-affiliate-password')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/payment'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/payment', 'affiliate-payment-options')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/tracking'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/tracking', 'affiliate-tracking-code')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/transaction'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/transaction', 'affiliate-transactions')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/logout'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/logout', 'affiliate-logout')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/forgotten'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/forgotten', 'affiliate-forgot-password')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/register'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/register', 'create-affiliate-account')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'affiliate/login'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'affiliate/login', 'affiliate-login')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'product/compare'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'product/compare', 'compare-products')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'product/search'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'product/search', 'search')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/edit'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/edit', 'edit-account')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/password'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/password', 'change-password')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/address'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/address', 'address-book')"; $this->db->query($query); }
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE query = 'account/reward'"); if (!$query->num_rows) { $query = "INSERT  INTO ".DB_PREFIX."url_alias (`query`, `keyword`) VALUES( 'account/reward', 'reward-points')"; $this->db->query($query); }

		$this->session->data['success'] = "Friendly urls were created.";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	

	public function seourls() {
	
		$this->session->data['success'] = '';
		
		$this->data['parameters'] = array();
		
		if (isset($this->request->post['parameters'])) {
			$this->data['parameters'] = $this->request->post['parameters'];
		} elseif ($this->config->get('parameters')) { 
			$this->data['parameters'] = $this->config->get('parameters');
		}
		
		$ext = $this->data['parameters']['ext'];
	
		$query = $this->db->query("SELECT ".DB_PREFIX."product_description.product_id, ".DB_PREFIX."product_description.name FROM ".DB_PREFIX."product, ".DB_PREFIX."product_description WHERE ".DB_PREFIX."product.product_id = ".DB_PREFIX."product_description.product_id and ".DB_PREFIX."product_description.language_id = ".$this->config->get('config_language_id'));

		$i = 0;
		foreach ($query->rows as $product_row ){	

			
			if( strlen($product_row['name']) > 1 ){
			
				$slug = $this->generateSlug($product_row['name']).$ext;
				$exist_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'product_id=" . $product_row['product_id'] . "'");
				
				if(!$exist_query->num_rows){
					
					$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
					if($exist_keyword->num_rows){ $slug = $this->generateSlug($product_row['name']).'-'.rand().$ext;}
						
					
					$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword) VALUES ('product_id=" . $product_row['product_id'] . "', '" . $slug . "')";
					$this->db->query($add_query);
					$i++;
				}
			}
		}			
		$this->session->data['success'] .=  $i . " new product friendly SEO urls were created <br>";	

		$query = $this->db->query("SELECT ".DB_PREFIX."category_description.category_id, ".DB_PREFIX."category_description.name FROM ".DB_PREFIX."category, ".DB_PREFIX."category_description WHERE ".DB_PREFIX."category.category_id = ".DB_PREFIX."category_description.category_id and ".DB_PREFIX."category_description.language_id = ".$this->config->get('config_language_id'));

		$i = 0;
		foreach ($query->rows as $category_row){	

			
			if( strlen($category_row['name']) > 1 ){
			
				$slug = $this->generateSlug($category_row['name']);
				$exist_query =  $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'category_id=" . $category_row['category_id'] . "'");
				
				if(!$exist_query->num_rows){
					
					$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
					if($exist_keyword->num_rows){ $slug = $this->generateSlug($category_row['name']).'-'.rand();}
						
					
					$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword) VALUES ('category_id=" . $category_row['category_id'] . "', '" . $slug . "')";
					$this->db->query($add_query);
					$i++;
				}
			}
		}			

		$this->session->data['success'] .= $i . " new category friendly SEO urls were created <br>";

		$query = $this->db->query("SELECT * from ".DB_PREFIX."manufacturer");
		$i = 0;

		foreach ($query->rows as $manufacturer_row){	

			
			if( strlen($manufacturer_row['name']) > 1 ){
				
				$slug = $this->generateSlug($manufacturer_row['name']);
				$exist_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'manufacturer_id=" . $manufacturer_row['manufacturer_id'] . "'");
				
				if(!$exist_query->num_rows){
					
					$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
					if($exist_keyword->num_rows){ $slug = $this->generateSlug($manufacturer_row['name']).'-'.rand();}
						
						
					$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword) VALUES ('manufacturer_id=" . $manufacturer_row['manufacturer_id'] . "', '" . $slug . "')";
					$this->db->query($add_query);
					$i++;
				}
			}
		}			

		$this->session->data['success'] .=  $i . " new manufacturer friendly SEO urls were created <br>";

		$query = $this->db->query("SELECT ".DB_PREFIX."information_description.information_id, ".DB_PREFIX."information_description.title FROM ".DB_PREFIX."information, ".DB_PREFIX."information_description WHERE ".DB_PREFIX."information.information_id = ".DB_PREFIX."information_description.information_id and ".DB_PREFIX."information_description.language_id = ".$this->config->get('config_language_id'));
		$i = 0;

		foreach ($query->rows as $info_row){	

			
			if( strlen($info_row['title']) > 1 ){
				
				$slug = $this->generateSlug($info_row['title']).$ext;
				$exist_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.query = 'information_id=" . $info_row['information_id'] . "'");
				
				if(!$exist_query->num_rows){
					
					$exist_keyword = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.keyword = '" . $slug . "'");
					if($exist_keyword->num_rows){ $slug = $this->generateSlug($info_row['title']).'-'.rand().$ext;}
						
					
					$add_query = "INSERT INTO " . DB_PREFIX . "url_alias (query, keyword) VALUES ('information_id=" . $info_row['information_id'] . "', '" . $slug . "')";
					$this->db->query($add_query);
					$i++;
				}
			}
		}			

		$this->session->data['success'] .=  $i . " new information friendly SEO urls were created <br>";
		
		$this->redirect($this->url->link('catalog/seopack', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
	
	public function generateSlug($phrase) {
	
	$cyr = array(
        "й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
        "г"=>"g","ш"=>"sh","щ"=>"sh","з"=>"z","х"=>"x","ъ"=>"\'",
        "ф"=>"f","ы"=>"i","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
        "о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"ie","ё"=>"e",
        "я"=>"ya","ч"=>"ch","с"=>"c","м"=>"m","и"=>"i","т"=>"t",
        "ь"=>"\'","б"=>"b","ю"=>"yu",
        "Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
        "Г"=>"G","Ш"=>"SH","Щ"=>"SH","З"=>"Z","Х"=>"X","Ъ"=>"\'",
        "Ф"=>"F","Ы"=>"I","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
        "О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"IE","Ё"=>"E",
        "Я"=>"YA","Ч"=>"CH","С"=>"C","М"=>"M","И"=>"I","Т"=>"T",
        "Ь"=>"\'","Б"=>"B","Ю"=>"YU"
    ); 
	
	$gr = array(
		"Β" => "V", "Γ" => "Y", "Δ" => "Th", "Ε" => "E", "Ζ" => "Z", "Η" => "E",
		"Θ" => "Th", "Ι" => "i", "Κ" => "K", "Λ" => "L", "Μ" => "M", "Ν" => "N",
		"Ξ" => "X", "Ο" => "O", "Π" => "P", "Ρ" => "R", "Σ" => "S", "Τ" => "T",
		"Υ" => "E", "Φ" => "F", "Χ" => "Ch", "Ψ" => "Ps", "Ω" => "O", "α" => "a",
		"β" => "v", "γ" => "y", "δ" => "th", "ε" => "e", "ζ" => "z", "η" => "e",
		"θ" => "th", "ι" => "i", "κ" => "k", "λ" => "l", "μ" => "m", "ν" => "n",
		"ξ" => "x", "ο" => "o", "π" => "p", "ρ" => "r", "σ" => "s", "τ" => "t",
		"υ" => "e", "φ" => "f", "χ" => "ch", "ψ" => "ps", "ω" => "o", "ς" => "s",
		"ς" => "s", "ς" => "s", "ς" => "s", "έ" => "e", "ί" => "i", "ά" => "a",
		"ή" => "e", "ώ" => "o", "ό" => "o"
	);
	
	$arabic = array(
		"ا"=>"a", "أ"=>"a", "آ"=>"a", "إ"=>"e", "ب"=>"b", "ت"=>"t", "ث"=>"th", "ج"=>"j",
		"ح"=>"h", "خ"=>"kh", "د"=>"d", "ذ"=>"d", "ر"=>"r", "ز"=>"z", "س"=>"s", "ش"=>"sh",
		"ص"=>"s", "ض"=>"d", "ط"=>"t", "ظ"=>"z", "ع"=>"'e", "غ"=>"gh", "ف"=>"f", "ق"=>"q",
		"ك"=>"k", "ل"=>"l", "م"=>"m", "ن"=>"n", "ه"=>"h", "و"=>"w", "ي"=>"y", "ى"=>"a",
		"ئ"=>"'e", "ء"=>"'",   
		"ؤ"=>"'e", "لا"=>"la", "ة"=>"h", "؟"=>"?", "!"=>"!", 
		"ـ"=>"", 
		"،"=>",", 
		"َ‎"=>"a", "ُ"=>"u", "ِ‎"=>"e", "ٌ"=>"un", "ً"=>"an", "ٍ"=>"en", "ّ"=>""
	);
	
	$persian = array(
		"ا"=>"a", "أ"=>"a", "آ"=>"a", "إ"=>"e", "ب"=>"b", "ت"=>"t", "ث"=>"th",
		"ج"=>"j", "ح"=>"h", "خ"=>"kh", "د"=>"d", "ذ"=>"d", "ر"=>"r", "ز"=>"z",
		"س"=>"s", "ش"=>"sh", "ص"=>"s", "ض"=>"d", "ط"=>"t", "ظ"=>"z", "ع"=>"'e",
		"غ"=>"gh", "ف"=>"f", "ق"=>"q", "ك"=>"k", "ل"=>"l", "م"=>"m", "ن"=>"n",
		"ه"=>"h", "و"=>"w", "ي"=>"y", "ى"=>"a", "ئ"=>"'e", "ء"=>"'", 
		"ؤ"=>"'e", "لا"=>"la", "ک"=>"ke", "پ"=>"pe", "چ"=>"che", "ژ"=>"je", "گ"=>"gu",
		"ی"=>"a", "ٔ"=>"", "ة"=>"h", "؟"=>"?", "!"=>"!", 
		"ـ"=>"", 
		"،"=>",", 
		"َ‎"=>"a", "ُ"=>"u", "ِ‎"=>"e", "ٌ"=>"un", "ً"=>"an", "ٍ"=>"en", "ّ"=>""
	);
	
	$normalize = array(
		'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
		'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
		'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
		'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
		'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
		'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
		'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'Ğ'=>'G', 'Ş'=>'S', 'Ü'=>'U',
		'ü'=>'u', 'Ẑ'=>'Z', 'ẑ'=>'z', 'Ǹ'=>'N', 'ǹ'=>'n', 'Ò'=>'O', 'ò'=>'o', 'Ù'=>'U', 'ù'=>'u', 'Ẁ'=>'W',
		'ẁ'=>'w', 'Ỳ'=>'Y', 'ỳ'=>'y'		
	);
	
	$result = html_entity_decode($phrase); 
	
	$result = strtr($result, $cyr);
	$result = strtr($result, $gr);
	$result = strtr($result, $arabic);
	$result = strtr($result, $persian);
	$result = strtr($result, $normalize);	
	$result = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $result);
	
	$result = strtolower($result);
	$result = str_replace('&', '-and-', $result);
	$result = str_replace('^', '', $result);
    $result = preg_replace("/[^a-z0-9-]/", "-", $result);
	$result = preg_replace('{(-)\1+}','$1', $result); 
	$result = trim(substr($result, 0, 800));
	$result = trim($result,'-'); //Thanks to LeXXoS
    
    return $result;
	}
	


	
}
?>