<?php

namespace Cart;

class CartHelper
{
	public function __construct($registry)
	{
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
	}

	public function calcPrice( $product, $options, &$price_out )
	{
		if( isset( $product['price'] ) )
		{
			$price_out['price'] = floatval( $product['price'] );
		}

		if( isset( $product['special'] ) )
		{
			$price_out['special'] = floatval( $product['special'] );
		}

		foreach( $options as $option )
		{
			if ( $option['type'] == 'select' || $option['type'] == 'radio')
			{
				$optval = current( $option['product_option_value'] );

				if( $optval['price_prefix'] == '+' )
				{
					if( isset( $price_out['price'] ) )
						$price_out['price'] += $optval['price'];

					if( isset( $price_out['special'] ) )
						$price_out['special'] += $optval['price'];

				}elseif( $optval['price_prefix'] == '-')
				{
					if( isset( $price_out['price'] ) )
						$price_out['price'] -= $optval['price'];

					if( isset( $price_out['special'] ) )
						$price_out['special'] -= $optval['special'];
				}
			}
		}
	}
}