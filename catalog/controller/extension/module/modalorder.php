<?php
class ControllerExtensionModuleModalOrder extends Controller {
	public function index($setting) {
		static $module = 0;		

		$times = explode( "\n", $setting['times'] );

		$data['times'] = array();

		$i = 1;

		foreach( $times as $time )
		{
			$time = trim( $time );

			if( !empty( $time ) )
				$data['times'][] = array( 'time' => $time, 'index' => $i++ );
		}

		$shops = explode( "\n", $setting['shops'] );

		$data['shops'] = array();

		$i = 1;

		foreach( $shops as $shop )
		{
			$shop = trim( $shop );

			if( !empty( $shop ) )
				$data['shops'][] = array( 'shop' => $shop, 'index' => $i++ );
		}

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

		foreach ($results as $result) {
			if ($this->config->get('total_' . $result['code'] . '_status')) {
				$this->load->model('extension/total/' . $result['code']);
				$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
			}
		}

		foreach( $totals as $tot )
		{
			if( $tot['code'] == 'sub_total' )
			{
				$totval = $tot['value'];
				$data['total'] = $this->currency->format( $tot['value'], $this->session->data['currency'] );
			}
		}

		$address = array( 'country_id' => 0, 'zone_id' => 0 );

		$this->load->model('extension/shipping/flat');

		$ship = $this->model_extension_shipping_flat->getQuote( $address );
		
		$ship = current( $ship['quote'] );


		$data['shipping']	= $this->currency->format( floatval( $ship['cost'] ) , $this->session->data['currency'] );
		$data['totalshipping'] = $this->currency->format( $totval + floatval( $ship['cost'] ) , $this->session->data['currency'] );

		$data['module'] = $module++;

		return $this->load->view( 'extension/module/modalorder', $data );
	}
}