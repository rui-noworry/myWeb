<?php
// 判断用户是否登录
// 如果登录返回PassportID 否则返回false
function isLogin() {

    $id = M('Auth')->where(array("a_id" => getPassportId()))->getField('a_id');
    return $id? $id: FALSE;
}

// 获取PassportID
function getPassportId() {

    if ($_COOKIE['passport_id']) {
        return intval(passport_decrypt($_COOKIE['passport_id'], ILC_ENCRYPT_KEY));
    } else {
        return 0;
    }
}

// 设置PassportID
function setPassportId($id) {
    if (is_null($id)) { // 销毁Passport
        Cookie('passport_id', NULL);
    } elseif (!empty($id)) { // 设置Passport
        $value = passport_encrypt($id, ILC_ENCRYPT_KEY);
        Cookie('passport_id', $value);
        return $value;
    }
}

function passport_encrypt($data, $key) {
    $key = md5($key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i ++) {
        if ($x == $l) $x = 0;
        $char .=substr($key, $x, 1);
        $x++;
    }
    for ($i = 0;$i < $len; $i ++) {
        $str .=chr(ord(substr($data,$i,1))+(ord(substr($char,$i,1)))%256);
    }
    return $str;
}

function passport_decrypt($data, $key) {
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);

    for ($i = 0; $i < $len; $i ++) {
        if ($x == $l) $x = 0;
        $char .=substr($key, $x, 1);
        $x++;
    }

    for ($i = 0;$i < $len; $i ++) {

        if (ord(substr($data,$i,1))<ord(substr($char,$i,1))) {

            $str .=chr((ord(substr($data,$i,1))+256)-ord(substr($char,$i,1)));
        } else {
            $str .=chr(ord(substr($data,$i,1))-ord(substr($char,$i,1)));
        }
    }
    return base64_decode($str);
}

// 中文字符串截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix='...') {
    if (strlen($str)<=3*$length) {
        return $str;
    }
    if (function_exists("mb_substr")) {
        return mb_substr($str, $start, $length, $charset) . $suffix;
    } elseif (function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset) . $suffix;
    }
    $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    return $slice . $suffix;
}
// 重载缓存数据
function reloadCache($name = 'config') {
    $cache = loadCache($name);
    if (!$cache) {
        switch ($name) {
            case 'config':
                $list  = M("Config")->getField('con_name,con_value');
                $cache = array_change_key_case($list, CASE_UPPER);
                break;
            case 'group':
                $cache = M("Group")->where(array('g_status' => 1))->order('g_sort')->getField('g_id,g_title');
                break;
            case 'model':
                $list  = M("Model")->where(array('m_status' => 1))->select();
                $array = array();
                foreach ($list as $key => $val){
                    $map['at_id'] = array('IN', $val['m_list']);
                    $val['attrs'] = M('Attribute')->where($map)->getField('at_id,at_name');
                    $cache[strtolower($val['m_name'])] = $val;
                }
                break;
            default:
                $list  = M("Config")->getField('con_name,con_value');
                $cache = array_change_key_case($list, CASE_UPPER);
        }
        saveCache($name, $cache);
    }

    C($cache);
    return $cache;
}

// 加载缓存数据
function loadCache($name) {
    switch(strtoupper(C('CACHE_TYPE'))) {
        case 'DB':// 数据库缓存
            $cache = dbCache($name);
            break;
        case 'XCACHE':// Xcache缓存
            $cache = xcache($name);
            break;
        case 'FILE':// 文件缓存
        default:
            $cache = include DATA_PATH . '~' . $name . '.php';
            break;
    }
    return $cache;
}

// 保存缓存数据
function saveCache($name, $value) {
    switch(strtoupper(C('CACHE_TYPE'))) {
        case 'DB':// 数据库缓存
            $result = dbCache($name, $value);
            break;
        case 'XCACHE':// Xcache缓存
            $result = xcache($name, $value);
            break;
        case 'FILE':// 文件缓存
        default:
            $result = file_put_contents(DATA_PATH . '~' . $name . '.php', "<?php return " . var_export($value, true) . ";?>");
            break;
    }
    return $result;
}

function cacheData() {

    // 获取学校管理员可操作的所有权限
    if (!$schoolNode = loadCache('schoolNode')) {
        $nodes = M("SchoolNode")->where(array('sn_status' => 1))->order('sn_sort ASC,sn_id ASC')->field('sn_id,sn_title,sn_name,sn_pid,sn_url,sn_sort,sn_level')->select();
        foreach ($nodes as $key => $val) {
            $schoolNode[$val['sn_id']] = $val;
        }
        saveCache('schoolNode', $schoolNode);
    }

    // 获取学校列表
    if (!$school = loadCache('school')) {
        $school = M("School")->where(array('s_status' => 1))->order('s_id ASC')->select();

        saveCache('school', setArrayByField($school, 's_id'));
    }

    if (!$model = loadCache('model')) {
        $list  = M("Model")->where(array('m_status' => 1))->select();
        $array = array();
        foreach ($list as $key => $val){
            $map['at_id'] = array('IN', $val['m_list']);
            $val['attrs'] = M('Attribute')->where($map)->getField('at_id,at_name');
            $cache[strtolower($val['m_name'])] = $val;
        }

        saveCache('model', $cache);
    }
}

/*
 * getValueByField
 * 获取数组字段值
 * @param array $array 数组 默认为 array()
 * @param string $field 字段名 默认为id
 *
 * @return array $result 数组(各字段值)
 *
 */
function getValueByField($array = array(), $field = 'id') {
    $result = array();
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $result[] = $value[$field];
        }
    }
    return $result;
}

/*
 * getDataByArray
 * 通过关联数组获取数据
 * @param string $table 表名
 * @param array $array 数组
 * @param string $arrayField 数组的字段
 * @param string $getField 要获取的字段
 *
 * @return array $result 获取的数据
 *      使用参考：通过活动获取对应的课时列表,传递M(课时), 活动数组及课时ID字段
 */
function getDataByArray($table, $array, $arrayField, $getField = '*') {
    $result = array();
    $result = M($table)->where(array($arrayField => array('IN', implode(',', getValueByField($array, $arrayField)))))->field($getField)->select();
    return setArrayByField($result, $arrayField);
}

/*
 * setArrayByField
 * 根据字段重组数组
 * @param array $array 数组 默认为 array()
 * @param string $field 字段名 默认为id
 *
 * @return array $result 重组好的数组
 *
 */
function setArrayByField($array = array(), $field = 'id') {
    $result = array();
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $result[$value[$field]] = $value;
        }
    }
    return $result;
}
?>