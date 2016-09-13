<?php
/*
 * @support
 * http://www.opensourcetechnologies.com/contactus.html
 * sales@opensourcetechnologies.com
* */
class ControllerModuleRecentPurchase extends Controller {
	public function index($setting) {
		$this->load->language('module/recentpurchase');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/recentpurchase');

		$this->load->model('tool/image');

		$data['products'] = array();
		$data['limit']= $setting['limit'];
		$data['width']= $setting['width'];
		$data['category_specific']=$setting['category_specific'];
		$category_id='';
		if($data['category_specific'])
		{
			if (isset($this->request->get['_route_'])) { 
				$parts = explode('/', $this->request->get['_route_']);

				// remove any empty arrays from trailing
				if (utf8_strlen(end($parts)) == 0) {
					array_pop($parts);
				}
	
				foreach ($parts as $part) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

					if ($query->num_rows) {
						$url = explode('=', $query->row['query']);
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
							} else {
								$this->request->get['path'] .= '_' . $url[1];
								
							}
							$category_id=$this->request->get['path'];
						}
					}
				}
			}
			else if(isset($this->request->get['path'])) $category_id=$this->request->get['path'];
		}
		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit'],
			'category_id'=>$category_id
		);
		//var_dump($filter_data);
		$results = $this->model_catalog_recentpurchase->getRecentPurchaseProducts($filter_data);
	
		//var_dump($results);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' =>$setting['description'] ? utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..' : '',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
			$data['no_item_slider']=$setting['no_item_slider'];
			if($setting['slider']) $tpl_file='recentpurchase_slider.tpl';
			else $tpl_file='recentpurchase.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$tpl_file)) {
				return $this->load->view($this->config->get('config_template') . '/template/module/'.$tpl_file, $data);
			} else {
				return $this->load->view('default/template/module/'.$tpl_file, $data);
			}
		}
	}
}
