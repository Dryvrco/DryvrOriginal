<?php

class ModelAccountBank extends Model {

    public function addBank($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "bank` SET bank_name = '" . $data['bank_name'] . "', routing_number = '" . $data['routing_number'] . "', account_number = '" . $data['account_number'] . "', account_title = '" . $data['account_title'] . "', swift_code = '" . $data['swift_code'] . "', agency_id = '" . $this->session->data['seller_id'] . "'");
    }

    public function editBank($bank_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "bank` SET bank_name = '" . $data['bank_name'] . "', routing_number = '" . $data['routing_number'] . "', account_number = '" . $data['account_number'] . "', account_title = '" . $data['account_title'] . "', swift_code = '" . $data['swift_code'] . "', agency_id = '" . $this->session->data['seller_id'] . "' WHERE bank_id = '" . $bank_id . "'");
    }

    public function checkBank($bank_id, $seller_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bank` WHERE bank_id = '" . (int) $bank_id . "' AND agency_id = '" . (int) $seller_id . "'");
        if ($query->num_rows>0){
            return 1;
        } else {
            return 0;
        }
    }

    public function editPassword($user_id, $password) {
        $this->db->query("UPDATE `" . DB_PREFIX . "agency` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . md5($password) . "', code = '' WHERE user_id = '" . (int) $user_id . "'");
    }

    public function editCode($email, $code) {
        $this->db->query("UPDATE `" . DB_PREFIX . "agency` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
    }

    public function deleteBank($bank_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "bank` WHERE bank_id = '" . (int) $bank_id . "'");
    }

    public function getBank($bank_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bank` u WHERE bank_id = '" . (int) $bank_id . "' AND agency_id = '" . $this->session->data['seller_id'] . "'");
        return $query->row;
    }

    public function getUserByUsername($username) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "agency` WHERE username = '" . $this->db->escape($username) . "' AND agency_id = '" . $this->session->data['seller_id'] . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "agency` WHERE code = '" . $this->db->escape($code) . "' AND code != '' AND agency_id = '" . $this->session->data['customer_id'] . "'");

        return $query->row;
    }

    public function getBanks($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "bank WHERE agency_id = '" . $this->session->data['customer_id'] . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalBanks() {
        $query = $this->db->query("SELECT COUNT(bank_id) AS total FROM " . DB_PREFIX . "bank WHERE agency_id = '" . $this->session->data['seller_id'] . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($user_group_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "agency` WHERE user_group_id = '" . (int) $user_group_id . "' AND agency_id = '" . $this->session->data['customer_id'] . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByEmail($email) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "agency` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND agency_id = '" . $this->session->data['customer_id'] . "'");

        return $query->row['total'];
    }

}
