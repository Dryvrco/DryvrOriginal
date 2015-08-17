<?php

class ModelModuleSlideshow extends Model {

    public function getCities($data = array()) {

        $sql = "SELECT city_id,name FROM " . DB_PREFIX . "city WHERE 1=1";

        if (!empty($data['filter_name'])) {
            $sql .= " AND name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_name_id'])) {
            $sql .= " AND cp.category_id = '" . $this->db->escape($data['filter_name_id']) . "'";
        }

        $sort_data = array(
            'name',
            'sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        
        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['city_id'];
        }
        return $list;
    }

    public function getCityName($id) {
        $sql = "SELECT name FROM " . DB_PREFIX . "city WHERE city_id = '" . (int) $id . "'";
        $query = $this->db->query($sql);
        return $query->row['name'];
    }

    public function getMakes() {
        $sql = "SELECT c.category_id , cd.name from " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd on (c.category_id = cd.category_id) WHERE c.type = 'mm' AND c.parent_id = '0' ORDER BY cd.name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['category_id'];
        }
        return $list;
    }
    
    public function getAddress($address_id) {
        $address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "'");

        if ($address_query->num_rows) {
            $country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $address_query->row['country_id'] . "'");

            if ($country_query->num_rows) {
                $country = $country_query->row['name'];
                $iso_code_2 = $country_query->row['iso_code_2'];
                $iso_code_3 = $country_query->row['iso_code_3'];
                $address_format = $country_query->row['address_format'];
            } else {
                $country = '';
                $iso_code_2 = '';
                $iso_code_3 = '';
                $address_format = '';
            }

            $zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $address_query->row['zone_id'] . "'");

            if ($zone_query->num_rows) {
                $zone = $zone_query->row['name'];
                $zone_code = $zone_query->row['code'];
            } else {
                $zone = '';
                $zone_code = '';
            }

            return array(
                'address_id' => $address_query->row['address_id'],
                'customer_id' => $address_query->row['customer_id'],
                'firstname' => $address_query->row['firstname'],
                'lastname' => $address_query->row['lastname'],
                'company' => $address_query->row['company'],
                'address_1' => $address_query->row['address_1'],
                'address_2' => $address_query->row['address_2'],
                'postcode' => $address_query->row['postcode'],
                'city' => $address_query->row['city'],
                'zone_id' => $address_query->row['zone_id'],
                'zone' => $zone,
                'zone_code' => $zone_code,
                'country_id' => $address_query->row['country_id'],
                'country' => $country,
                'iso_code_2' => $iso_code_2,
                'iso_code_3' => $iso_code_3,
                'address_format' => $address_format,
                'custom_field' => unserialize($address_query->row['custom_field'])
            );
        }
    }
    
    public function getAddresses($customer_id) {
        $address_data = array();

        $query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

        foreach ($query->rows as $result) {
            $address_info = $this->getAddress($result['address_id']);

            if ($address_info) {
                $address_data[$result['address_id']] = $address_info;
            }
        }

        return $address_data;
    }

    public function getCats() {
        $sql = "SELECT c.category_id , cd.name from " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd on (c.category_id = cd.category_id) WHERE c.type = 'cat' ORDER BY cd.name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['category_id'];
        }
        return $list;
    }

    public function getAgencies() {
        $sql = "SELECT seller_id,nickname from " . DB_PREFIX . "ms_seller WHERE seller_status = '1' AND seller_approved = '1'";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['nickname']] = $result['seller_id'];
        }
        return $list;
    }

    public function getSubCategories($id) {

        if ($id != '0') {
            $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int) $this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sql .= " AND c1.parent_id = '" . $id . "'";

            $sql .= " GROUP BY cp.category_id";

            $sort_data = array(
                'name',
                'sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY sort_order";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            $query = $this->db->query($sql);
            foreach ($query->rows as $result) {
                $list[$result['name']] = $result['category_id'];
            }
            $opts = mkdd($list, $subid);
        }
    }

}
