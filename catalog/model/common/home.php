<?php

class ModelCommonHome extends Model {

    public function getAgencies() {
        $query = "SELECT seller_id,nickname,avatar FROM " . DB_PREFIX . "ms_seller WHERE seller_featured = '1' AND seller_status = '1' AND seller_approved = '1'";
        $result = $this->db->query($query);
        return $result->rows;
    }

    public function getAgenciesCars() {
        $query = "SELECT p.image, p.product_id,p.model FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "ms_product msp on (p.product_id = msp.product_id) INNER JOIN " . DB_PREFIX . "ms_seller mss on (mss.seller_id = msp.seller_id) WHERE mss.seller_featured = '1' AND mss.seller_status = '1' AND mss.seller_approved = '1' AND mss.seller_status = '1' AND mss.seller_approved = '1' ORDER BY rand() LIMIT 0,8";
        $result = $this->db->query($query);
        return $result->rows;
    }

    public function getAllCities() {

        $query = "SELECT city_id, name FROM " . DB_PREFIX . "city order by city_id ASC";
        $result = $this->db->query($query);
        return $result->rows;
    }

    public function getMakes() {

        $query = "SELECT c.category_id,cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON(c.category_id=cd.category_id) WHERE c.parent_id = '0' AND c.type = 'mm' order by cd.name ASC";
        $result = $this->db->query($query);
        return $result->rows;
    }

}
