<?php
class ControllerInformationVacancies extends Controller {
	public function index() {
		$this->load->language('information/vacancies');

		$this->load->model('catalog/vacancies');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['vacancy_id'])) {
			$vacancy_id = (int)$this->request->get['vacancy_id'];
		} else {
			$vacancy_id = 0;
		}

		$vacancies_info = $this->model_catalog_vacancies->getInformation($vacancy_id);

		if ($vacancies_info) {
			$this->document->setTitle($vacancies_info['meta_title']);
			$this->document->setDescription($vacancies_info['meta_description']);
			$this->document->setKeywords($vacancies_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $vacancies_info['title'],
				'href' => $this->url->link('information/vacancies', 'vacancy_id=' .  $vacancy_id)
			);

			$data['heading_title'] = $vacancies_info['title'];

			$data['description'] = html_entity_decode($vacancies_info['description'], ENT_QUOTES, 'UTF-8');

			$date = date_parse( $vacancies_info['date_added'] );

			$data['date'] = sprintf("%02d.%02d.%04d", $date['day'], $date['month'], $date['year'] );

			if( $vacancies_info['image'] )
			{
				$data['image'] = $vacancies_info['image'];
			}

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer_vacancy');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/vacancies', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('information/vacancies', 'vacancy_id=' . $vacancy_id)
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
		$this->load->model('catalog/vacancies');

		if (isset($this->request->get['vacancy_id'])) {
			$vacancy_id = (int)$this->request->get['vacancy_id'];
		} else {
			$vacancy_id = 0;
		}

		$output = '';

		$vacancies_info = $this->model_catalog_vacancies->getInformation($vacancy_id);

		if ($vacancies_info) {
			$output .= html_entity_decode($vacancies_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
		}

		$this->response->setOutput($output);
	}
}