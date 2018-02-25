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

			$data['vacancy_id'] = $vacancy_id;

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

	public function ajax()
	{
		$vacancy_id = intval( $this->request->post['vacancy_id'] );

		if( $vacancy_id > 0 )
		{
			$this->load->model('catalog/vacancies');

			$vacancy = $this->model_catalog_vacancies->getInformation( $vacancy_id );

			$name = trim( $this->request->post['name'] );
			$phone = trim( $this->request->post['phone'] );
			$email = trim( $this->request->post['email'] );
			$comment = trim( $this->request->post['comment'] );

			$subject = "Отклик на вакансию ". $vacancy['title'];

			$message = $name . " откликнулся на вакансию " . $vacancy['title'] . "\n";
			$message .= "Телефон: " . $phone . "\n";

			if( !empty( $email ) )
			{
				$message .= "E-mail: " . $email ."\n";
			}

			if( !empty( $comment ) )
			{
				$message .= "Комментарий: " . $comment . "\n";
			}

			$mail = new Mail();

			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo( $this->config->get('config_email') );
			$mail->setFrom( $this->config->get('config_email') );
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
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