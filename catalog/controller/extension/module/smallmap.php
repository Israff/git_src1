<?php
class ControllerExtensionModuleSmallMap extends Controller {
	public function index($setting) {
		static $module = 0;		

		$data['map'] = html_entity_decode( $setting['map'], ENT_QUOTES, "UTF-8" );
		$data['text'] = html_entity_decode( $setting['text'], ENT_QUOTES, "UTF-8" );
		$data['title'] = html_entity_decode( $setting['title'], ENT_QUOTES, "UTF-8" );

		$data['module'] = $module++;

		return $this->load->view('extension/module/smallmap', $data);
	}
}