<?php

class ModelLocalisationArea extends Model {

    public function addArea($data) {
        $query = "INSERT INTO " . DB_PREFIX . "area SET status = '" . (int) $data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', city_id = '" . (int) $data['city_id'] . "'";

        $this->db->query($query);
        $this->cache->delete('city');
    }

    public function editArea($area_id, $data) {
        $query = "UPDATE " . DB_PREFIX . "area SET status = '" . (int) $data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', city_id = '" . (int) $data['city_id'] . "' WHERE area_id = '" . (int) $area_id . "' ";
        $this->db->query($query);
        $this->cache->delete('area');
    }

    public function deleteArea($area_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "area WHERE area_id = '" . (int) $area_id . "'");
        $this->cache->delete('area');
    }

    public function getArea($area_id) {
        //$query = $this->db->query("SELECT DISTINCT *, ct.code, ct.name FROM " . DB_PREFIX . "city ct LEFT JOIN " . DB_PREFIX . "zone z ON (ct.zone_id = z.zone_id)  WHERE ct.city_id = '" . (int) $city_id . "'");
        $query = $this->db->query("SELECT a.area_id,a.code,a.status,a.name,c.city_id,z.zone_id,coun.country_id FROM " . DB_PREFIX . "area a LEFT JOIN " . DB_PREFIX . "city c on (c.city_id=a.city_id) LEFT JOIN " . DB_PREFIX . "zone z on (z.zone_id=c.zone_id) LEFT JOIN " . DB_PREFIX . "country coun on (coun.country_id=z.country_id) WHERE area_id = '" . (int) $area_id . "'");

        return $query->row;
    }

    public function getSellerId($product_id) {
        $sql = "SELECT seller_id FROM " . DB_PREFIX . "ms_product WHERE product_id = '" . (int) $product_id . "'";
        $query = $this->db->query($sql);
        return $query->row['seller_id'];
    }

    public function getAreas($data = array()) {

        $sql = "SELECT a.area_id,a.code,a.name as area_name,c.name as city_name,a.status FROM " . DB_PREFIX . "area a LEFT JOIN " . DB_PREFIX . "city c ON (c.city_id = a.city_id)";

        $sort_data = array(
            'city_name',
            'area_name',
            'a.code',
            'a.status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY area_name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
        }

        $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAvailableCities($seller_id) {
        $sql = "SELECT c.city_id, c.name FROM " . DB_PREFIX . "city c LEFT JOIN " . DB_PREFIX . "ms_seller mss on (c.zone_id=mss.zone_id) WHERE mss.seller_id = '" . $seller_id . "'";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['city_id'];
        }
        return $list;
    }

    public function getZonesByCountryId($country_id) {
        $zone_data = $this->cache->get('zone.' . (int) $country_id);

        if (!$zone_data) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int) $country_id . "' AND status = '1' ORDER BY name");

            $zone_data = $query->rows;

            $this->cache->set('zone.' . (int) $country_id, $zone_data);
        }

        return $zone_data;
    }

    public function getTotalAreas() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "area");
        return $query->row['total'];
    }

    public function getTotalZonesByCountryId($country_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int) $country_id . "'");

        return $query->row['total'];
    }

    public function getZones($country_id) {
        $sql = "SELECT zone_id,name FROM " . DB_PREFIX . "zone WHERE country_id = '" . $country_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['zone_id'];
        }
        $opts = mkdd($list);
    }

    public function getZonesList($zone_id) {
        $sql = "SELECT zone_id,name FROM " . DB_PREFIX . "zone WHERE country_id = '" . $zone_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['zone_id'];
        }
        return $list;
    }

}
