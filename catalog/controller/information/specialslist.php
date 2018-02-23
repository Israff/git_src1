<?php

class ControllerInformationSpecialsList extends Controller {
	public function index()
	{
		$this->load->language( 'information/specialslist' );

		$this->load->model( 'catalog/specialslist' );
		$this->load->model( 'tool/image' );

		if( isset( $this->request->get['page'] ) )
		{
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		$limit = 100;

		$data['specials'] = array();

		$filter_data = array(
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$data['sub_title'] = $this->language->get( 'sub_title' );

		$specials_total = intval(  $this->model_catalog_specialslist->getTotalSpecials() );

		$results = $this->model_catalog_specialslist->getSpecials( $filter_data );

		$width = 540;
		$height = 299;

		foreach( $results as $result )
		{
			if( $result['image'] )
			{
				$image = $this->model_tool_image->resize( $result['image'], $width, $height );
			}else{
				$image = $this->model_tool_image->resize( 'placeholder.png', $width, $height );
			}

			$date = date_parse( $result['date_added'] );

			$data['specials'][] = array(
				'special_id'  => $result['information_id'],
				'date_added'	=> sprintf( "%02.%02.%04d", $date['day'], $date['month'], $date['year'] ),
				'thumb'       => $image,
				'title'        => $result['title'],
				'description' => utf8_substr( trim( strip_tags( html_entity_decode( $result['description'], ENT_QUOTES, 'UTF-8' ) ) ), 0, 220 ) . '..',
				'href'        => $this->url->link('information/specials', 'special_id=' . $result['information_id'] )
			);
		}

		$url = '';

		$pagination = new Pagination();
		$pagination->total = $specials_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('information/specialslist',  $url . '&page={page}');

		$data['pagination'] = $pagination->render(); 

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput( $this->load->view( 'information/specialslist', $data ) );
	}
}