<?php

class ModelAccountReviews extends Model {

    public function getTotalReviews($seller_id) {
        $query = "SELECT AVG(r.rating) as rating FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "ms_product msp ON (r.product_id = msp.product_id) WHERE msp.seller_id = '" . (int) $seller_id . "' AND r.status = '1'";
        $result = $this->db->query($query);
        return $result->row['rating'];
    }

}
