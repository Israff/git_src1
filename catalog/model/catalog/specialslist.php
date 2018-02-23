<?php

class ModelCatalogSpecialsList extends Model {

	public function getTotalSpecials()
	{
		$sql = "SELECT COUNT(*) as count FROM `" . DB_PREFIX . "information` WHERE `type` = 'S' AND `status` = '1' ;";

		$result = $this->db->query( $sql );

		return $result->row['count'];
	}

	public function getSpecials( $filter_data )
	{
		$sql = "SELECT i.*, id.`title`, id.`description` FROM `".DB_PREFIX . "information` as i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE i.`type` = 'S' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1' ORDER BY i.`sort_order` LIMIT " . $filter_data['start'] . ',' . $filter_data['limit'] . ";";

		$result = $this->db->query( $sql );

		return $result->rows;
	}
}