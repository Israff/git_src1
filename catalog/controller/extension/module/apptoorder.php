<?php
class ControllerExtensionModuleAppToOrder extends Controller {
	public function index($setting) {
		static $module = 0;

		$data['title']	= $setting['name'];

		$count = intval( $setting['count'] );

		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$data['products'] = array();

		$this->load->library('cart/carthelper');

		$results = $this->cart->getProducts();

		$products = array();
		$related = array();

		foreach( $results as $result )
		{
			$products[ $result['product_id'] ] = 1;
		}

		$products = array_keys( $products );

		foreach( $products as $product_id )
		{
			$tmp = $this->model_catalog_product->getProductRelated( $product_id );
			
			$related = array_merge( $related, array_keys( $tmp ) );
		}

		$related = array_unique( $related );

		$related = array_diff( $related, $products );

		if( count( $related ) == 0 )
			return '';

		shuffle( $related );

		$i = 0;
		
		foreach( $related as $product_id )
		{
			$i++;

			if( $i >= $count )
				break;
			
			$result = $this->model_catalog_product->getProduct( $product_id );

			$options = $this->model_catalog_product->getProductOptions( $result['product_id'] );

			$sticker = false;

			$price = false;
			$special = false;

			$m0r1_options = array();

			foreach( $options as $opt )
			{
				if( $opt['type'] == 'radio' && $opt['required'] == 1 )
				{
					if( count( $opt['product_option_value'] ) > 0 )
					{
						$optval = current( $opt['product_option_value'] );

						$i = 0;

						do
						{
							$option = $opt;

							$name = $optval['name'];

							if( $i == 0 )
								$option['first'] = true;
							else
								$option['first'] = false;

							$i++;

							$option['product_option_value'] = array( $optval );

							$moptions = array( $option );

							$prices = array();

							
							$this->carthelper->calcPrice( $result, $moptions, $prices );

							
							if( $price === false && isset( $prices['price'] ) && ( $this->customer->isLogged() || !$this->config->get( 'config_customer_price' ) ) )
							{
								$price = $this->currency->format( $this->tax->calculate( $prices['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );
							}

							if( $special === false && isset( $prices['special'] ) && (float)$prices['special'] )
							{
								$special = $this->currency->format( $this->tax->calculate( $prices['special'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );
							}

							$push = array();

							if( isset( $prices['price'] ) && ( $this->customer->isLogged() || !$this->config->get( 'config_customer_price' ) ) )
							{
								$push['price'] = $this->currency->format( $this->tax->calculate( $prices['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );

								if( isset( $prices['special'] ) && (float)$prices['special'] )
								{
									$push['special'] = $this->currency->format( $this->tax->calculate( $prices['special'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );
								}

								$option['product_option_value_id'] = $optval['product_option_value_id'];

								unset( $option['price'], $option['product_option_value'] );

								$option['name'] = html_entity_decode( $name, ENT_QUOTES, "UTF-8" );

								$option = array_merge( $push, $option );

								$m0r1_options[] = $option;
							}

						}while( ( $optval = next( $opt['product_option_value'] ) ) !== FALSE );
					}
				}

				if( $opt['type'] == 'select' && $opt['required'] == 0 )
				{
					$optval = current( $opt['product_option_value'] );

					if( !empty( $optval['image'] ) )
						$sticker = $optval['image'];
				}
			}

			if( $result['image'] )
			{
				$image = $this->model_tool_image->resize( $result['image'], $setting['width'], $setting['height'] );
			}else{
				$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height'] );
			}

			if( $price === FALSE && ( $this->customer->isLogged() || !$this->config->get( 'config_customer_price' ) ) )
			{
				$price = $this->currency->format( $this->tax->calculate( $result['price'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );
			}

			if( $special === FALSE && (float)$result['special'] )
			{
				$special = $this->currency->format( $this->tax->calculate( $result['special'], $result['tax_class_id'], $this->config->get( 'config_tax' ) ), $this->session->data['currency'] );
			}

			$data['products'][] = array(
				'product_id'  	=> $result['product_id'],
				'thumb'			=> $image,
				'name'        	=> $result['name'],
				'description' 	=> utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
				'calorific'		=> html_entity_decode( $result['calorific'], ENT_QUOTES, 'UTF-8'),
				'price'       	=> $price,
				'special'     	=> $special,
				'options'		=> $m0r1_options,
				'sticker'		=> $sticker
			);
		}

		$data['module'] = $module++;

		return $this->load->view( 'extension/module/apptoorder', $data );
	}
}