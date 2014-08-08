<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function array_xml($arr, $num_prefix = "num_") {
        if(!is_array($arr)) return $arr;
        $result = '';
        foreach($arr as $key => $val) {
                $key = (is_numeric($key)? $num_prefix.$key : $key);
                $result .= '<'.$key.'>'.array_xml($val, $num_prefix).'</'.$key.'>';
        }
        return $result;
}

?>