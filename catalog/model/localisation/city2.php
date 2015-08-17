<?php

class ModelLocalisationCity extends Model {

    public function getCities() {
        $sql = "SELECT c.city_id, c.name FROM " . DB_PREFIX . "city c LEFT JOIN " . DB_PREFIX . "ms_seller mss on (c.zone_id=mss.zone_id) WHERE mss.seller_id = '" . $this->session->data['customer_id'] . "'";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result){
            $list[$result['name']] = $result['city_id'];
        }
        return $list;
    }

}
