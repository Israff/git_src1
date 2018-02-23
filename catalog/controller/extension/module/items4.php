<?php
class ControllerExtensionModuleItems4 extends Controller {
	public function index($setting) {
		static $module = 0;		

		$this->load->model('tool/image');

		
		$data['items'] = array();

		for( $i = 1; $i <= 4; $i++ )
		{
			if( !empty( $setting[ 'image' . $i ] ) )
			{
				$data['items'][] = array(
					'image'	=> $setting[ 'image' . $i ],
					'html'	=> html_entity_decode( $setting[ 'html' . $i ], ENT_QUOTES, "UTF-8" )
				);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/items4', $data);
	}
}