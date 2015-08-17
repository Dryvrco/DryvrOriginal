<?php

function test(){
    return 1;
}

function noofdays($start, $end) {
    $end_date = strtotime($end);
    $start_date = strtotime($start);
    $datediff = $end_date - $start_date;
    return floor($datediff / (60 * 60 * 24)) + 1;
}

function calculaterate($hours, $daily, $weekly, $monthly) {
    $price = '';

    if ($hours >= 720) {
        if ($monthly > 0) {
            $price = $hours / 720 * $monthly;
        } else if ($weekly > 0) {
            $price = $hours / 168 * $weekly;
        } else if ($daily > 0) {
            $price = $hours / 24 * $daily;
        }
    }

    if ($hours >= 168 && $hours < 720) {
        if ($weekly > 0) {
            $price = $hours / 168 * $weekly;
        } else if ($daily > 0) {
            $price = $hours / 24 * $daily;
        }
    }

    if ($hours >= 1 && $hours < 168) {
        $price = $hours / 24 * $daily;
    }

    return $price;
}

function calculatehours($start_date, $start_time, $end_date, $end_time) {
    $date1 = $start_date . ' ' . $start_time;
    $date2 = $end_date . ' ' . $end_time;
    $timestamp1 = strtotime($date1);
    $timestamp2 = strtotime($date2);
    $hours = abs($timestamp2 - $timestamp1) / (60 * 60);
    return ceil($hours / 24) * 24;
}

function pre($val) {
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}

function summary($str, $limit = 200) {
    if (strlen($str) > $limit) {
        $str = substr($str, 0, $limit) . '...';
    }
    return trim($str);
}

function convertdate($date) {
    $datestr = explode('/', $date);
    return $datestr[2] . '-' . $datestr[0] . '-' . $datestr[1];
}

function getStatus($sel) {
    $data = array();
    $data['Active'] = 1;
    $data['Inactive'] = 0;
    echo mkdd($data, $sel);
}

function mkdd($data = array(), $selected = '') {
    $response = "";
    foreach ($data as $key => $val) {
        if ($selected == $val) {
            $response .= '<option value="' . $val . '" selected>' . $key . '</option>';
        } else {
            $response .= '<option value="' . $val . '">' . $key . '</option>';
        }
    }
    echo $response;
}

function convert12to24($time) {
    return date("H:i", strtotime($time));
}

function convert24to12($time) {
    return date("h:i A", strtotime($time));
}
