<?php

class ModelLocalisationCity extends Model {

    public function addCity($data) {
        $query = "INSERT INTO " . DB_PREFIX . "city SET status = '" . (int) $data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int) $data['zone_id'] . "'";

        $this->db->query($query);
        $this->cache->delete('city');
    }

    public function editCity($city_id, $data) {
        $query = "UPDATE " . DB_PREFIX . "city SET status = '" . (int) $data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int) $data['zone_id'] . "' WHERE city_id = '" . (int) $city_id . "' ";
        $this->db->query($query);
        $this->cache->delete('city');
    }

    public function deleteCity($city_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "city WHERE city_id = '" . (int) $city_id . "'");

        $this->cache->delete('city');
    }

    public function getCity($city_id) {
        $query = $this->db->query("SELECT DISTINCT *, ct.code, ct.name FROM " . DB_PREFIX . "city ct LEFT JOIN " . DB_PREFIX . "zone z ON (ct.zone_id = z.zone_id)  WHERE ct.city_id = '" . (int) $city_id . "'");

        return $query->row;
    }

    public function getSellerId($product_id) {
        $sql = "SELECT seller_id FROM " . DB_PREFIX . "ms_product WHERE product_id = '" . (int) $product_id . "'";
        $query = $this->db->query($sql);
        return $query->row['seller_id'];
    }

    public function getCities($data = array()) {
        //   $sql = "SELECT *, z.name, c.name AS country FROM " . DB_PREFIX . "zone z LEFT JOIN " . DB_PREFIX . "country c ON (z.country_id = c.country_id)";

        $sql = "SELECT *, ct.name, z.name AS zone, ct.code FROM " . DB_PREFIX . "city ct LEFT JOIN " . DB_PREFIX . "zone z ON (ct.zone_id = z.zone_id)";

        /*   $sort_data = array(
          'c.name',
          'z.name',
          'z.code'
          );

          if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
          $sql .= " ORDER BY " . $data['sort'];
          } else {
          $sql .= " ORDER BY c.name";
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

          $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
          } */

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

    public function getTotalCities() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city");

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

    public function getCitiesList($zone_id) {
        $sql = "SELECT city_id,name FROM " . DB_PREFIX . "city WHERE zone_id = '" . $zone_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['city_id'];
        }
        return $list;
    }

    public function getAreasList($city_id) {
        $sql = "SELECT area_id,name FROM " . DB_PREFIX . "area WHERE city_id = '" . $city_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['area_id'];
        }
        return $list;
    }

    public function getCbAreasList($city_id) {
        $sql = "SELECT area_id,name FROM " . DB_PREFIX . "area WHERE city_id = '" . $city_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getCitieswrtZone($zone_id) {
        $sql = "SELECT city_id,name FROM " . DB_PREFIX . "city WHERE zone_id = '" . $zone_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['city_id'];
        }
        $opts = mkdd($list);
    }

    public function getAreaswrtCity($city_id) {
        $sql = "SELECT area_id,name FROM " . DB_PREFIX . "area WHERE city_id = '" . $city_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $list = array();
        foreach ($query->rows as $result) {
            $list[$result['name']] = $result['area_id'];
        }
        $opts = mkdd($list);
    }

    public function getCbAreaswrtCity($city_id) {
        $sql = "SELECT area_id,name FROM " . DB_PREFIX . "area WHERE city_id = '" . $city_id . "' ORDER BY name ASC";
        $query = $this->db->query($sql);
        if ($query->rows) {
            foreach ($query->rows as $result) {
                echo "<div class='col-sm-3'><input type='checkbox' name='seller[area][]' value='" . $result['area_id'] . "' /> " . $result['name'] . "</div>";
            }
        } else {
            echo 'No areas available.';
        }
    }

}
