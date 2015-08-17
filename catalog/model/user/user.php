<?php

class ModelUserUser extends Model {

    public function addUser($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "ms_seller` SET nickname = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "', seller_status = '" . $data['status'] . "', seller_approved = '1'");
        $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET customer_group_id = '" . (int) $data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . md5($data['password']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', status = '" . $data['status'] . "', approved = '1', agency_id = '" . $this->session->data['seller_id'] . "', date_added = NOW()");
    }

    public function editUser($user_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET customer_group_id = '" . (int) $data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', agency_id = '" . $this->session->data['customer_id'] . "', status = '" . $data['status'] . "' WHERE customer_id = '" . (int) $user_id . "'");
        $this->db->query("UPDATE `" . DB_PREFIX . "ms_seller` SET nickname = '" . $this->db->escape($data['firstname']) . ' ' . $this->db->escape($data['lastname']) . "' WHERE seller_id = '" . (int) $user_id . "'");

        if ($data['password'] != '') {
            $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . md5($data['password']) . "' WHERE customer_id = '" . (int) $user_id . "'");
        }
    }

    public function checkUser($user_id, $seller_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int) $user_id . "' AND agency_id = '" . (int) $seller_id . "'");
        if ($query->num_rows > 0) {
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

    public function deleteUser($user_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "ms_seller` WHERE seller_id = '" . (int) $user_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . (int) $user_id . "'");
    }

    public function getUser($user_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $user_id . "'");
        return $query->row;
    }

    public function getUserByUsername($username) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "agency` WHERE username = '" . $this->db->escape($username) . "' AND agency_id = '" . $this->session->data['customer_id'] . "'");

        return $query->row;
    }

    public function getUserByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "agency` WHERE code = '" . $this->db->escape($code) . "' AND code != '' AND agency_id = '" . $this->session->data['customer_id'] . "'");

        return $query->row;
    }

    public function getUsers($data = array()) {
        $sql = "SELECT c.customer_id,c.customer_group_id,c.firstname,c.lastname,c.status,c.date_added,ag.name FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "agency_group ag ON (c.customer_group_id = ag.customer_group_id) WHERE c.agency_id = '" . $this->session->data['seller_id'] . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsers() {
        $query = $this->db->query("SELECT COUNT(c.customer_id) AS total FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "agency_group ag ON (c.customer_group_id = ag.customer_group_id) WHERE c.agency_id = '" . $this->session->data['seller_id'] . "'");

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
