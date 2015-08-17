<?php

class ModelAccountReservations extends Model {

    public function getOrders($data = array()) {
        $sql = "SELECT o.order_id,o.start_date,o.start_time,op.product_id,op.model,o.firstname,o.lastname,o.email,o.order_status_id,o.date_added,os.name FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id=o.order_id) LEFT JOIN " . DB_PREFIX . "ms_suborder mso ON (o.order_id=mso.order_id) LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id=os.order_status_id) WHERE mso.seller_id = '" . $this->session->data['seller_id'] . "' AND o.start_date >= CURDATE() ORDER BY o.start_date ASC";

        if (!empty($data['limit'])) {
            $sql .= " LIMIT 0," . $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(o.order_id) AS total FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "ms_suborder mso ON (o.order_id=mso.order_id) LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id=os.order_status_id) WHERE mso.seller_id = '" . $this->session->data['seller_id'] . "' AND o.start_date >= CURDATE()";
        $query = $this->db->query($sql);
        return $query->row['total'];
    }

}
