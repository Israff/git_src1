<?php

class ControllerCheckoutM0r1 extends Controller {

	public function index()
	{
		$guest = array();

		$guest['customer_group_id'] = 1;
		$guest['firstname'] = $guest['lastname'] = $this->request->post['name'];
		$guest['email'] = 'test@mail.ru';
		$guest['telephone'] = $this->request->post['phone'];
		$guest['custom_field'] = '';

		$this->session->data['guest'] = $guest;

		$payment = array();
		$shipping  = array();

		$payment['firstname'] = $shipping['firstname'] = $this->request->post['name'];
		$payment['lastname'] = $shipping['lastname'] = $this->request->post['name'];
		$payment['company'] = $shipping['company'] = '';

		$delivery = $this->request->post['delivery'];

		if( $delivery == 'flat' )
		{
			$street = trim( $this->request->post['street'] );
			$house = trim( $this->request->post['house'] );
			$appartment = trim( $this->request->post['appartment'] );

			$address = array();

			if( !empty( $street ) )
			{
				$address[] = "ул. " . $street;
			}

			if( !empty( $house ) )
			{
				$address[] = "д. " . $house;
			}

			if( !empty( $appartment ) )
			{
				$address[] = "кв. " . $appartment;
			}

			$address = implode( " , " , $address );
		}else{
			$address = 'Магазин: ' . $this->request->post['shop'];
		}

		$payment['address_1'] = $shipping['address_1'] = $address;
		$payment['address_2'] = $shipping['address_2'] = '';

		$payment['city'] = $shipping['city'] = '';
		$payment['postcode'] = $shipping['postcode'] = '';
		$payment['zone'] = $shipping['zone'] = '';
		$payment['zone_id'] = $shipping['zone_id'] = 0;
		$payment['country'] = $shipping['country'] = '';
		$payment['country_id'] = $shipping['country_id'] = 0;
		$payment['address_format'] = $shipping['address_format'] = '';
		$payment['custom_field'] = $shipping['custom_field'] = '';

		$this->session->data['shipping_address'] = $shipping;
		$this->session->data['payment_address'] = $payment;

		$comment = array();

		$bonus = trim( $this->request->post['bonus'] );
		$date = trim( $this->request->post['date'] );
		$time = trim( $this->request->post['time'] );
		$comment_r = trim( $this->request->post['comment'] );

		if( !empty( $bonus ) )
		{
			$comment[] = "Номер бонусной карты: " . $bonus;
		}

		if( !empty( $date ) )
		{
			$comment[] = "Дата доставки: " . $date;
		}

		if( !empty( $time ) )
		{
			$comment[] = "Время доставки: " . $time;
		}

		if( !empty( $comment_r ) )
		{
			$comment[] = "Комментарий: " . $comment_r;
		}

		$this->session->data['comment'] = implode( "\n", $comment );

		$this->load->model('setting/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		$results = $this->model_setting_extension->getExtensions('total');

		foreach ($results as $result)
		{
			if ($this->config->get('total_' . $result['code'] . '_status'))
			{
				$this->load->model('extension/total/' . $result['code']);
				$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
			}
		}

		$method_data = array();

		$results = $this->model_setting_extension->getExtensions('payment');

		foreach ($results as $result)
		{
			if ($this->config->get('payment_' . $result['code'] . '_status'))
			{
				$this->load->model('extension/payment/' . $result['code']);

				$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

				if ($method)
				{
					if( $result['code'] == 'cod' )
					{
						$this->session->data['payment_method'] = $method;
					}

					$method_data[$result['code']] = $method;
				}
			}
		}

		$this->session->data['payment_methods'] = $method_data;

		$method_data = array();

		$results = $this->model_setting_extension->getExtensions('shipping');

		foreach ($results as $result)
		{
			if ($this->config->get('shipping_' . $result['code'] . '_status'))
			{
				$this->load->model('extension/shipping/' . $result['code']);

				$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

				if ($quote)
				{
					if( $result['code'] == $delivery )
					{
						$this->session->data['shipping_method'] = current( $quote['quote'] );
					}

					$method_data[$result['code']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$this->session->data['shipping_methods'] = $method_data;

		$ret = array('success' => 1);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput( json_encode( $ret ) );
	}
}