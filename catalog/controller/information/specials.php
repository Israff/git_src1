<?php
class ControllerInformationSpecials extends Controller {
	public function index() {
		$this->load->language('information/specials');

		$this->load->model('catalog/specials');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['special_id'])) {
			$special_id = (int)$this->request->get['special_id'];
		} else {
			$special_id = 0;
		}

		$specials_info = $this->model_catalog_specials->getInformation($special_id);

		if ($specials_info) {
			$this->document->setTitle($specials_info['meta_title']);
			$this->document->setDescription($specials_info['meta_description']);
			$this->document->setKeywords($specials_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $specials_info['title'],
				'href' => $this->url->link('information/specials', 'special_id=' .  $special_id)
			);

			$data['heading_title'] = $specials_info['title'];

			$data['description'] = html_entity_decode($specials_info['description'], ENT_QUOTES, 'UTF-8');

			$date = date_parse( $specials_info['date_added'] );

			$data['date'] = sprintf("%02d.%02d.%04d", $date['day'], $date['month'], $date['year'] );

			if( $specials_info['image'] )
			{
				$data['image'] = $specials_info['image'];
			}

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/specials', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/specials', 'special_id=' . $special_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function agree() {
		$this->load->model('catalog/specials');

		if (isset($this->request->get['special_id'])) {
			$special_id = (int)$this->request->get['special_id'];
		} else {
			$special_id = 0;
		}

		$output = '';

		$specials_info = $this->model_catalog_specials->getInformation($special_id);

		if ($specials_info) {
			$output .= html_entity_decode($specials_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}