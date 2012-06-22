<?php

/*
  Document   : redirect
  Created on : 20-jun-2012, 20:53:46
  Author     : Glenn Blom <glennblom@gmail.com>
  Copyright © 2012 GB Web Productions
 */

class ControllerRedirectRedirect extends Controller {

    public function index() {
        $this->language->load('product/category');

        $this->load->model('catalog/product');
        $product = $this->model_catalog_product->getProduct($this->request->get['product_id']);

        $this->data['url'] = $product['redirect'];

        $this->template = 'default/template/redirect/redirect.tpl';
        $this->response->setOutput($this->render());
    }

}

?>