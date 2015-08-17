<?php

class ModelAgencyAgency extends Model {

    public function getSellerDetails($id) {
        $query = "SELECT count(*) as total, mss.nickname, mss.description, mss.avatar, c.name as country_name, z.name as zone_name FROM " . DB_PREFIX . "ms_product msp LEFT JOIN " . DB_PREFIX . "ms_seller mss ON (msp.seller_id = mss.seller_id) LEFT JOIN " . DB_PREFIX . "country c ON (mss.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (mss.zone_id = z.zone_id) WHERE mss.seller_id = '" . (int) $id . "'";
        $result = $this->db->query($query);
        return $result->rows;
    }

    public function getNearbyAreas($agency_id) {
        $query = "SELECT area FROM " . DB_PREFIX . "ms_seller WHERE seller_id = '" . (int) $agency_id . "'";
        $result = $this->db->query($query);
        $areas = $result->row['area'];
        if ($areas != '') {
            $area = explode(',', $areas);
            $area_array = array();
            foreach ($area as $area_id) {
                $sql = $this->db->query("SELECT name FROM " . DB_PREFIX . "area WHERE area_id = '" . (int) $area_id . "'");
                $area_array[] = $sql->row['name'];
            }
            return $area_array;
        } else {
            return '';
        }
    }

    public function getAllSellerDetails($data = array()) {
        $query = "SELECT mss.seller_id,mss.avatar,mss.description,mss.nickname,coun.name as country,z.name as zone FROM " . DB_PREFIX . "ms_seller mss LEFT JOIN " . DB_PREFIX . "customer c ON (c.agency_id = mss.seller_id) LEFT JOIN " . DB_PREFIX . "country coun ON (mss.country_id = coun.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (mss.zone_id = z.zone_id) WHERE c.customer = '0' AND (c.customer_id = c.agency_id) AND mss.seller_approved = '1' AND mss.seller_status = '1' AND c.approved = '1'";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $query .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $result = $this->db->query($query);
        return $result->rows;
    }

    public function getAllSellers() {
        $query = "SELECT count(mss.seller_id) as total FROM " . DB_PREFIX . "ms_seller mss LEFT JOIN " . DB_PREFIX . "customer c ON (c.agency_id = mss.seller_id) WHERE c.customer = '0' AND (c.customer_id = c.agency_id) AND mss.seller_approved = '1' AND mss.seller_status = '1' AND c.approved = '1'";
        $result = $this->db->query($query);
        return $result->row['total'];
    }

    public function AddFilters($data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "agency_filter WHERE agency_id = '" . (int) $this->agency->getSellerId() . "'");

        if (isset($data['product_filter'])) {
            foreach ($data['product_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "agency_filter SET agency_id = '" . (int) $this->agency->getSellerId() . "', filter_id = '" . (int) $filter_id . "'");
            }
        }
    }

    public function getAgencyCars($id, $data = array()) {
        $sql = "SELECT p.product_id, p.model, p.image,pd.description,p.daily,p.weekly,p.monthly FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "ms_product msp ON (p.product_id = msp.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd on (p.product_id=pd.product_id) WHERE msp.seller_id = '" . (int) $id . "' AND p.status = '1' AND msp.product_status = '1'";

        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        }

        if (isset($data['order'])) {
            $sql .= " " . $data['order'];
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $result = $this->db->query($sql);
        return $result->rows;
    }

    public function getAgencyReview($id) {
        $query = "SELECT AVG(r.rating) as rating FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "ms_product msp ON (r.product_id = msp.product_id) WHERE msp.seller_id = '" . (int) $id . "' AND r.status = '1'";
        $result = $this->db->query($query);
        return $result->row['rating'];
    }

    public function getTotalAgencyCars($id) {
        $query = "SELECT count(msp.product_id) as total FROM " . DB_PREFIX . "ms_product msp LEFT JOIN " . DB_PREFIX . "product p ON (msp.product_id=p.product_id) WHERE msp.seller_id = '" . (int) $id . "' AND p.status = '1' AND msp.product_status = '1'";
        $result = $this->db->query($query);
        return $result->row['total'];
    }

}
