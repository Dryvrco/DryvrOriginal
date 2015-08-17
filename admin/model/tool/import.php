<?php

/**
 * TMD(http://opencartextensions.in/)
 *
 * Copyright (c) 2006 - 2012 TMD
 * This package is Copyright so please us only one domain 
 * 
 */
class ModelToolImport extends Model {

    public function category($category, $parent_id) {

        $pos = strpos($category, '>');
        if ($pos === false) {
            $category = trim($category);
            $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.name = '" . $this->db->escape($category) . "' and c.parent_id='" . $parent_id . "'");

            if ($query->row) {
                return $query->row['category_id'];
            } else {

                $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int) $parent_id . "', `top` = '" . (isset($data['top']) ? (int) $data['top'] : 0) . "', `column` = '0', sort_order = '0', status = '1', date_modified = NOW(), date_added = NOW()");

                $category_id = $this->db->getLastId();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
                foreach ($query->rows as $languages) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . $languages['language_id'] . "', name = '" . $this->db->escape($category) . "', meta_keyword = '" . $this->db->escape($category) . "', meta_description = '" . $this->db->escape($category) . "', description = '" . $this->db->escape($category) . "'");
                }
                $level = 0;

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $parent_id . "' ORDER BY `level` ASC");

                foreach ($query->rows as $result) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $result['path_id'] . "', `level` = '" . (int) $level . "'");

                    $level++;
                }

                $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $category_id . "', `level` = '" . (int) $level . "'");

                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '0'");

                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $this->db->escape(str_replace("'", '', $category)) . "'");
                if ($query->row) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape(str_replace("'", '', $category)) . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($category) . '-' . $category_id . "'");
                }
                return $category_id;
            }
        } else {
            $categories = explode('>', $category);
            $count = count($categories);
            $parent_id1 = 0;
            for ($r = 0; $r <= $count; $r++) {
                if (!empty($categories[$r])) {
                    $parent_id1 = $this->category($categories[$r], $parent_id1);
                }
            }
            return $parent_id1;
        }
    }

    public function barnd($barnd) {
        $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $barnd . "'");

        if ($query->row) {
            return $query->row['manufacturer_id'];
        } else {

            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($barnd) . "', sort_order = '0'");

            $manufacturer_id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int) $manufacturer_id . "', store_id = '0'");

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $barnd . "'");
            if ($query->row) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($barnd) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int) $manufacturer_id . "', keyword = '" . $this->db->escape($barnd) . '-' . $manufacturer_id . "'");
            }
            return $manufacturer_id;
        }
    }

    public function option($data) {

        $option = explode(":", $data);
        $optionname = $option[0];
        $type = $option[1];

        $query = $this->db->query("select * from  " . DB_PREFIX . "option_description where  name = '" . $this->db->escape($optionname) . "' limit 0,1");
        if ($query->row) {
            return $query->row['option_id'];
        } else {

            $this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = '" . $type . "', sort_order = '0'");

            $option_id = $this->db->getLastId();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
            foreach ($query->rows as $languages) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int) $option_id . "', language_id = '" . (int) $languages['language_id'] . "', name = '" . $this->db->escape($optionname) . "'");
            }
            return $option_id;
        }
    }

    public function optionvalue($data) {
        $option = explode(":", $data);
        $optionname = $option[0];

        $query = $this->db->query("select * from  " . DB_PREFIX . "option_description where  name = '" . $this->db->escape($optionname) . "' limit 0,1");
        $option_id = $query->row['option_id'];

        ////// Option value set 
        $optionvalues = explode("-", $data);
        $optionvaluename = explode(':', $optionvalues[0]);
        $optionvaluename = $optionvaluename[1];
        $qty = $optionvalues[1];
        $subtract = $optionvalues[2];
        $price = $optionvalues[3];
        $points = $optionvalues[4];
        $weight = $optionvalues[5];
        if (isset($optionvalues[6])) {
            $sort_order = $optionvalues[6];
        } else {
            $sort_order = 0;
        }


        $query = $this->db->query("select  * from  " . DB_PREFIX . "option_value_description where option_id = '" . (int) $option_id . "' and name = '" . $this->db->escape($optionvaluename) . "'");
        if ($query->row) {
            $option_value_id = $query->row['option_value_id'];
            $this->db->query("update " . DB_PREFIX . "option_value  set sort_order = '" . $sort_order . "' where option_value_id='" . $option_value_id . "'");
        } else {

            $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int) $option_id . "', sort_order = '" . $sort_order . "'");

            $option_value_id = $this->db->getLastId();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
            foreach ($query->rows as $languages) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int) $option_value_id . "', language_id = '" . (int) $languages['language_id'] . "', option_id = '" . (int) $option_id . "', name = '" . $this->db->escape($optionvaluename) . "'");
            }
        }
        $data = array(
            'option_id' => $option_id,
            'option_value_id' => $option_value_id,
            'qty' => $qty,
            'subtract' => $subtract,
            'price' => $price,
            'points' => $points,
            'weight' => $weight,
        );
        return $data;
        ////// Option value set 
    }

    public function imagesave($image) {

        $pos = strpos($image, '=');
        if ($pos === false) {

            $path = 'data/productimage/';

            if (!file_exists(DIR_IMAGE . $path)) {
                @mkdir(DIR_IMAGE . $path, 0777);
            }
        } else {
            $image = explode('=', $image);
            $path = 'data/' . $image[0] . '/';

            if (!file_exists(DIR_IMAGE . $path)) {
                @mkdir(DIR_IMAGE . $path, 0777);
            }
            $image = $image[1];
        }


        $pos = strpos($image, 'http://');
        if ($pos === false) {
            $imagepath = $image;
            //$imagepath1=DIR_IMAGE.$path.$image;
        } else {
            $handlerr = curl_init($image);
            curl_setopt($handlerr, CURLOPT_RETURNTRANSFER, TRUE);
            $resp = curl_exec($handlerr);
            $ht = curl_getinfo($handlerr, CURLINFO_HTTP_CODE);
            $imagename = explode('/', $image);
            $count = count($imagename);
            $image = $imagename[$count - 1];
            $imagepath = $path . $image;
            $imagepath1 = DIR_IMAGE . $path . $image;
            // Write the contents back to the file
            @file_put_contents($imagepath1, $resp);
        }
        return $imagepath;
    }

    public function getproductbymodel($model) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product where model='" . $model . "'");
        if ($query->row) {
            return $query->row['product_id'];
        }
    }

    public function addProduct($data) {

        $this->event->trigger('pre.admin.product.add', $data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET make_id = '" . $this->db->escape($data['make_id']) . "',model_id = '" . $this->db->escape($data['model_id']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '999', subtract = '0', shipping = '0', status = '" . (int) $data['status'] . "', date_added = NOW(), daily = '" . $data['daily'] . "', weekly = '" . $data['weekly'] . "', monthly = '" . $data['monthly'] . "' , delivery = '" . $data['delivery'] . "', airport = '" . $data['airport'] . "', min_age = '" . $data['min_age'] . "', insurance = '" . $data['insurance'] . "', security = '" . $data['security'] . "'");

        $product_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "ms_product SET product_id = '" . (int) $product_id . "', product_status = '" . (int) $data['status'] . "', product_approved = '1'");

        $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', description = '" . $this->db->escape($data['description']) . "', language_id = '1', meta_title = '" . $this->db->escape($data['meta_title']) . "'");

        $this->cache->delete('product');

        $this->event->trigger('post.admin.product.add', $product_id);

        return $product_id;
    }

    public function editProduct($data,$product_id) {
        $this->event->trigger('pre.admin.product.edit', $data);

        $this->db->query("UPDATE " . DB_PREFIX . "product SET make_id = '" . $this->db->escape($data['make_id']) . "',model_id = '" . $this->db->escape($data['model_id']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '999', subtract = '0', shipping = '0', status = '" . (int) $data['status'] . "', date_added = NOW(), daily = '" . $data['daily'] . "', weekly = '" . $data['weekly'] . "', monthly = '" . $data['monthly'] . "' , delivery = '" . $data['delivery'] . "', airport = '" . $data['airport'] . "', min_age = '" . $data['min_age'] . "', insurance = '" . $data['insurance'] . "', security = '" . $data['security'] . "' WHERE product_id = '" . (int) $product_id . "'");

        $this->db->query("UPDATE " . DB_PREFIX . "ms_product SET product_status = '" . (int) $data['status'] . "' WHERE product_id = '" . (int) $product_id . "'");

        $this->db->query("UPDATE " . DB_PREFIX . "product_description SET description = '" . $this->db->escape($data['description']) . "', meta_title = '" . $this->db->escape($data['meta_title']) . "' WHERE product_id = '" . (int) $product_id . "'");

        $this->cache->delete('product');

        $this->event->trigger('post.admin.product.edit', $product_id);
    }

    public function getsubcategory($category_id) {
        $category_ids = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path where category_id='" . $category_id . "'");
        if ($query->rows) {
            foreach ($query->rows as $categroy) {
                $category_ids[] = $categroy['path_id'];
            }
        }
        return $category_ids;
    }

    public function filtergroup($filtergroup) {
        $data = explode(":", $filtergroup);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description where name='" . $this->db->escape($data[0]) . "'");
        if (!$query->row) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int) $data[1] . "'");
            $filter_group_id = $this->db->getLastId();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
            foreach ($query->rows as $languages) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int) $filter_group_id . "', language_id = '" . (int) $languages['language_id'] . "', name = '" . $this->db->escape($data[0]) . "'");
            }
        }
    }

    public function filtername($filtername) {
        $filter_id = '';
        $datafull = explode("=", $filtername);
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description where name='" . $this->db->escape($datafull[0]) . "'");
        if ($query->row) {
            $filter_group_id = $query->row['filter_group_id'];
        }
        $data = explode(":", $datafull[1]);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description where name='" . $this->db->escape($data[0]) . "' and filter_group_id = '" . (int) $filter_group_id . "'");
        if (!$query->row) {

            $this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int) $filter_group_id . "', sort_order = '" . (int) $data[1] . "'");

            $filter_id = $this->db->getLastId();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
            foreach ($query->rows as $languages) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int) $filter_id . "', language_id = '" . (int) $languages['language_id'] . "', filter_group_id = '" . (int) $filter_group_id . "', name = '" . $this->db->escape($data[0]) . "'");
            }
        } else {
            $filter_id = $query->row['filter_id'];
        }
        return $filter_id;
    }

    public function atributeallinfo($attribute) {
        $data = array();
        $groupinfo = explode('=', $attribute);
        $groupinfo1 = explode(':', $groupinfo[0]);
        $groupname = '';
        if (isset($groupinfo1[0])) {
            $groupname = $groupinfo1[0];
        }
        $groupsortorder = '';
        if (isset($groupinfo1[1])) {
            $groupsortorder = $groupinfo1[1];
        }
        $attinfo = '';
        if (isset($groupinfo[1])) {
            $attinfo = explode('-', $groupinfo[1]);
        }

        if (isset($attinfo[0])) {
            $attname = '';
            if (isset($attinfo[0])) {
                $attname = $attinfo[0];
            }
            $text = '';
            if (isset($attinfo[1])) {
                $text = $attinfo[1];
            }
            $attsortorder = '';
            if (isset($attinfo[2])) {
                $attsortorder = $attinfo[2];
            }
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description where name='" . $this->db->escape($groupname) . "' and language_id = '" . (int) $this->config->get('config_language_id') . "'");
            if ($query->row) {
                $attribute_group_id = $query->row['attribute_group_id'];
            } else {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET sort_order = '" . (int) $groupsortorder . "'");
                $attribute_group_id = $this->db->getLastId();
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
                foreach ($query->rows as $languages) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int) $attribute_group_id . "', language_id = '" . (int) $languages['language_id'] . "', name = '" . $this->db->escape($groupname) . "'");
                }
            }
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description where name='" . $this->db->escape($attname) . "' and language_id = '" . (int) $this->config->get('config_language_id') . "'");
            if ($query->row) {
                $attribute_id = $query->row['attribute_id'];
            } else {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET sort_order='" . $attsortorder . "', attribute_group_id ='" . $attribute_group_id . "'");
                $attribute_id = $this->db->getLastId();
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");
                foreach ($query->rows as $languages) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET  language_id = '" . (int) $languages['language_id'] . "',attribute_id='" . $attribute_id . "', name = '" . $this->db->escape($attname) . "'");
                }
            }
            $data = array(
                'attribute_id' => $attribute_id,
                'text' => $text
            );
        }
        return $data;
    }

    public function clean($string) {
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(amp|acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = str_replace('amp', '', $string);
        $string = str_replace(',', '', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace('%', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace('', '-', $string);
        $string = str_replace(' ', '-', $string);
        $string = str_replace('--', '-', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);
        return strtolower(trim($string, '-'));
    }

}

?>