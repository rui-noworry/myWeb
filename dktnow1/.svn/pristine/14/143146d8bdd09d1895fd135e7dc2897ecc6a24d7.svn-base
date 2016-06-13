<?php
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

// 获取字符串后几位，不足补字符
function getStrLast($res, $num = 6, $str = '0') {

    $res = str_repeat($str, $num) . $res;
    return substr($res, -$num, $num);
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

// 获取用户信息
function member($a_id, $field = '') {

    $a_id = empty($a_id)? getPassportId() : $a_id;

    if(empty($field)) {
        $field =  '*';
    }

    return M('Auth')->where(array('a_id' => $a_id))->field($field)->find();
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


/*
 * replaceClassTitle
 * 转换班级名称
 * @param int $type 学制
 * @param int $grade 年级
 * @param string $title 班级名称
 * @param int $is_graduation 是否毕业 默认9：未毕业
 * @param int $ma_id 专业 0为小中高，大于1的为大学
 *
 * @return string $title 班级名称
 *
 */
function replaceClassTitle($s_id, $type, $grade, $title = '', $is_graduation = 0, $ma_id = 0) {

    $schoolType = C('SCHOOL_TYPE');
    $gradeType = C('GRADE_TYPE');
    $major = '';

    if ($title) {
        $title = '(' . $title . ')班';
    }

    if ($ma_id) {
        $major = M('Major')->where(array('ma_id' => $ma_id, 's_id' => $s_id))->getField('ma_title');
    }

    if ($is_graduation) {
        return GradeToYear($grade, $s_id) . '届' . $major . $title;
    } else {

        if ($grade > 100) {
            $grade = YearToGrade($grade, $s_id);
        }
        return getShortTitle($schoolType[$type], 1) . getShortTitle($gradeType[$type][$grade], 1) . $major . $title;
    }
}

function getShortTitle($title, $length=12, $stat='') {
    return msubstr($title,0,$length,'utf-8',$stat);
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

// 年级转换为年份 2(年级) => 2011
function GradeToYear($grade, $s_id) {

    $result = getXq($s_id);

    // 上学期
    if ($result['cc_xq'] == 1 ) {
        return intval(date('Y')) - intval($grade) + 1;
    } else {
        return intval(date('Y')) - intval($grade);
    }
}

// 年份转换为年级 2011级 => 2(年级)
function YearToGrade($year, $s_id) {

    $result = getXq($s_id);
    // 上学期
    if ($result['cc_xq'] == 1 ) {
        return intval(date('Y')) - $year + 1;
    } else {
        return intval(date('Y')) - $year;
    }
}

// 获取学期
function getXq($s_id) {

    $sId = $s_id;

    // 年份：2012
    $syYear = intval(date('Y', time()));

    // 上一年：2011
    $preYear = $syYear - 1;

    $time = C('SEMESTER_DEFAULT');

    $nowTime = time();

    $thisData = M("SchoolYear")->where(array('s_id' => $sId, 'sy_year' => $syYear))->find();

    if (!$thisData) {

        $thisData = setSchoolYear($syYear, $sId);
    }

    $preData = M("SchoolYear")->where(array('s_id' => $sId, 'sy_year' => $preYear))->find();

    if (!$preData) {
        $preData = setSchoolYear($preYear, $sId);
    }

    // 下届未开学， 本届下学期
    if ($nowTime < $preData['sy_down_end'] && $nowTime > $preData['sy_up_end']) {

        $result['cc_year'] = $preYear;
        $result['cc_xq'] = 2;

    } else if ($nowTime < $preData['sy_up_end']){

        // 2012年上学期
        $result['cc_year'] = $preYear;
        $result['cc_xq'] = 1;

    } else {
        // 2013年上学期
        $result['cc_year'] = $syYear;
        $result['cc_xq'] = 1;
    }

    return $result;
}

// 设置学年
function setSchoolYear($syYear, $sId) {

    $time = C('SEMESTER_DEFAULT');

    // 下一年：2013
    $nextYear = intval($syYear) + 1;

    $data['s_id'] = $sId;
    $data['sy_year'] = $syYear;

    $data['sy_up_start'] = strtotime($syYear.$time['sy_up_start']);
    $data['sy_up_end'] = strtotime($nextYear.$time['sy_up_end']);
    $data['sy_down_start'] = strtotime($nextYear.$time['sy_down_start']);
    $data['sy_down_end'] = strtotime($nextYear.$time['sy_down_end']);

    M("SchoolYear")->add($data);

    return $data;
}

// 通过学校类型获取年级
function getGradeByType($type, $s_id, $grade_id = 0) {

    // 获取配置参数
    $grade = C('GRADE_TYPE');

    // 获取当前学校
    $school = loadCache('school');
    $schooType = explode(',', $school[$s_id]['s_type']);

    $result = array();

    // 验证学校类型
    if (in_array($type, $schooType)) {

        if ($grade_id) {
            $result = $grade[$type][$grade_id];
        } else {
            $result = $grade[$type];
        }
    }

    return $result;
}

// 通过ID获取对应类型名称
function getTypeNameById($id, $type) {

    $type = C($type);

    if (strpos($id, ',') !== FALSE) {
        $id = explode(',', $id);
    }

    if (is_array($id)) {
        foreach ($id as $value) {
            $return[] = $type[$value];
        }

        return implode(',', $return);
    } else {

        if ($type[$id]) {
            $result = $type[$id];
        } else {
            $result = '';
        }
        return $result;
    }
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from, $to) {

    $from = strtoupper($from) == 'UTF8'? 'utf-8': $from;
    $to   = strtoupper($to)   == 'UTF8'? 'utf-8': $to;

    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }

    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding ($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if($key != $_key )
                unset($fContents[$key]);
        }
        return $fContents;
    } else {
        return $fContents;
    }
}

/*
 * getListByPage
 * 根据页码获取列表
 * @param string $table 表名
 * @param string $order 排序
 * @param array $where 条件 默认为array()
 * @param int $num 每页显示数量 默认为10
 * @param int $ajax 是否AJAX
 * @return array $result 数组
 *         + array $result['list'] 结果集
           + string $result['page'] 分页
 */
function getListByPage($table, $order, $where = array(), $num = 10, $ajax = 0, $p = "") {

    // 初始化参数
    $_GET['p'] = intval($_GET['p'])? intval($_GET['p']) : 1;
    $num = intval($num) > 0 ? intval($num) : 10;

    if ($p) {
        $_GET['p'] = intval($p)? intval($p) : 1;
    }

    $Source = M($table);
    $count= $Source->where($where)->count();

    // 要返回的数组
    $result = array();

    // 获取总页数
    $regNum = ceil($count / $num);

    // 验证当前请求页码是否大于总页数
    if ($_GET['p'] > $regNum) {
        return $result;
    }

    if (intval($ajax)) {
        import("@.ORG.Util.AjaxPage");
        $Page = new AjaxPage($count,$num);
    } else {
        import("@.ORG.Util.Page");
        $Page = new Page($count,$num);
    }

    $result['page'] = trim($Page->show());
    $result['list'] = $Source->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
    return $result;
}

// 过滤
function flipParam() {
    if ($_POST) {
        $_POST = flip($_POST);
    }

    if ($_GET) {
        $_GET = flip($_GET);
    }

    if ($_REQUEST) {
        $_REQUEST = flip($_REQUEST);
    }
}

// 过滤
function flip($arr) {
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $arr[$key] = flip($value);
        } else {
            $arr[$key] = htmlspecialchars(addslashes(trim($value)));
        }
    }
    return $arr;
}

/*
 * getCourseCover
 * 获取课程封面
 * @param string $fileName 封面名称
 * @param int $type  存放在配置文件中科目ID，
 * @param int $num  封面的尺寸目录
 *
 */
function getCourseCover($fileName = '', $type = 0, $num = 210) {

    if (!$fileName) {
        $fileName = ($type ? $type : 'default' ) . '.png';
    }

    $path = C('COURSE_COVER_PATH');
    $dir = $num ? ($num . '/') : '';

    $filePath = $path . $dir . $fileName;

    if (!file_exists($filePath)){
        $filePath = $path . 'default.png';
    }

    return turnTpl($filePath);
}

// 获取虚拟群组的LOGO
function getCrowdLogo($fileName, $num = 96) {

    if (!$fileName) {
        $fileName = 'default.jpg';
    }

    $path = C('CROWD_LOGO_PATH');
    $dir = $num ? ($num . '/') : '';

    $filePath = $path . $dir . $fileName;

    if (!file_exists($filePath)){
        $filePath = $path . 'default.jpg';
    }

    return turnTpl($filePath);
}

function turnTpl($content) {
    $replace = C('TMPL_PARSE_STRING');
    $content = str_replace(array_keys($replace), array_values($replace), $content);
    return $content;
}

// 获取学校LOGO
function getSchoolLogo($fileName, $num) {

    $path = C('SCHOOL_LOGO');
    $dir = $num ? ($num . '/') : '';

    $filePath = $path . $dir . $fileName;

    if (!file_exists($filePath)){
        $filePath = $path . 'default.jpg';
    }

    return turnTpl($filePath);
}

// 获取班级LOGO
function getClassLogo($fileName, $num = 96) {

    if (!$fileName) {
        $fileName = 'default.jpg';
    }

    $path = C('CLASS_LOGO');
    $dir = $num ? ($num . '/') : '';

    $filePath = $path . $dir . $fileName;

    if (!file_exists($filePath)){
        $filePath = $path . 'default.jpg';
    }

    return turnTpl($filePath);
}

/**
 * getAuthAvatar
 *  获取用户头像
 * */
function getAuthAvatar($avatar, $type = 1, $sex = 1, $num = 48) {

    if ($avatar) {
        $return = $avatar;
    } else {
        $return = $type . $sex . '.png';
    }

    $return = C('AUTH_AVATAR') . $num . '/' . $return;

    if (!file_exists($return)){
        $return = C('AUTH_AVATAR') . $num . '/' . 'default.jpg';
    }

    return turnTpl($return);
}

// 时间戳日期格式化
function toDate($time, $format='Y-m-d H:i:s') {
    if (empty($time)) {
        return '';
    }
    $format = str_replace('#', ':', $format);
    return date(($format), $time);
}

// 通过学校ID获取学校名称
function getSchoolNameById($sid) {
    $school = loadCache('school');
    return $school[$sid]['s_name'];
}

// 智慧豆
function addBean($a_id, $s_id, $c_id, $obj_type, $obj_id, $b_num, $b_name, $b_status = 1, $b_action = 0) {

    if (!$a_id || !$s_id || !$c_id || !$obj_type || !$obj_id || !$b_name) {
        return ;
    }

    $data['a_id'] = $a_id;
    $data['s_id'] = $s_id;
    $data['c_id'] = $c_id;
    $data['obj_type'] = $obj_type;
    $data['obj_id'] = $obj_id;
    $data['b_num'] = $b_num;
    $data['b_status'] = $b_status;
    $data['b_action'] = $b_action;
    $data['b_name'] = $b_name;
    $data['b_created'] = time();

    M('Bean')->add($data);

    if ($b_status) {
        M('Auth')->where(array('a_id' => $a_id))->setInc('a_bean', $b_num);
    } else {
        M('Auth')->where(array('a_id' => $a_id))->setDec('a_bean', $b_num);
    }
}

// 根据字段排序
function sortByField($arr, $field) {

    sort($arr);
    $count = count($arr);
    for ($i = 0; $i < $count; $i ++) {

        for ($j = $count-1; $j > $i; $j --) {

            if ($arr[$j][$field] > $arr[$i][$field]) {

                $tmp = $arr[$j];
                $arr[$j] = $arr[$i];
                $arr[$i] = $tmp;
            }
        }
    }

    return $arr;
}

//根据文件扩展名获取文件类型
function getFileTypeByExt($ext) {

    $allowType = C('ALLOW_FILE_TYPE');
    $ext = strtolower($ext);
    foreach ($allowType as $key => $value){
        if (in_array($ext, $value)){
            return $key;
        }
    }
}

// 获取目录下的所有文件
function getFiles($dir) {
    $files = array();
    if(!is_dir($dir)) {
        return $files;
    }
    $handle = opendir($dir);
    if($handle) {
        while(false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $filename = $dir.$file;
                if(is_file($filename)) {
                    $files[] = $filename;
                }else {
                    $files = array_merge($files,getFiles($filename));
                }
            }
        }
        closedir($handle);
    }
    return $files;
}

// 模拟账号
function generateAccount($str, $num = 17) {

     $start = '1' . getStrLast('', $num - strlen($str) - 1);
     $end = getStrLast('', $num - strlen($str), 9);

     return $str . rand($start, $end);
}

// 删除目录
function deleteDirectory($path) {
    import('@.ORG.Io.Dir');
    if(is_dir($path)) {
        Dir::delDir($path);
    }
}

// 获取活动类型
function getActivityType($type = 0) {

    $obj = C('OBJECT_TYPE');

    if ($type) {
        $return = $obj[$type];
        $return['id'] = $type;
    } else {
        $return = $obj;
    }

    return $return;
}

/*
 * addTrend
 * 添加动态
 * @param int $a_id 操作者
 * @param int $s_id 所在学校
 * @param int $c_id 所在班级
 * @param int $tr_action 动作 TREND_TYPE
 * @param int $tr_obj 操作对象 作业、练习等 TREND_TYPE
 * @param int $tr_to_id 对象ID，当对象是操作者自己时，为0
 * @param int $tr_course 学科ID，没有的话，为0
 * @param string $tr_title 标题
 * @param int $tr_obj_id ID号
 *
 */
function addTrend($a_id, $s_id, $c_id, $tr_action, $tr_obj, $tr_to_id = 0, $tr_course = 0, $tr_title = '', $tr_obj_id) {

    $data['a_id'] = $a_id;
    $data['s_id'] = $s_id;
    $data['c_id'] = $c_id;
    $data['tr_action'] = $tr_action;
    $data['tr_obj'] = $tr_obj;
    $data['tr_to_id'] = $tr_to_id;
    $data['tr_course'] = $tr_course;
    $data['tr_title'] = $tr_title;
    $data['tr_obj_id'] = $tr_obj_id;
    $data['tr_created'] = time();

    M('Trend')->add($data);

}

/*
 * download
 * 下载
 * @param string $filePath 文件相对路径 例: /Uploads/test/
 * @param string $fileName 下载文件名称 例: uploads
 * @param string $ext  文件后缀名 例: rar
 *
 */
function download($filePath, $fileName, $ext, $flag = true) {
    if ($flag) {
        $filePath = $filePath . $fileName . '.' . $ext;
    }
    $filesize = filesize($filePath);
    $downloadType = C('DOWNLOAD_TYPE');
    $type = $downloadType[$ext] ? $downloadType[$ext] : 'octet-stream';
    // fopen读取文件，重新输出
    if ($handle = fopen($filePath, "r")) {

        Header("Content-type:text/html;charset=utf8");
        Header("Content-type: application/" . $type);
        Header("Accept-Ranges: bytes");
        Header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        Header("Pragma: public");
        Header("Content-Length: ". $filesize);
        Header("Content-Disposition: attachment; filename=" . $fileName . '.' . $ext);
        readfile($filePath);
        fclose($handle);
        clearstatcache();
        exit();
    } else {
        Header('Location: http://'.$_SERVER['HTTP_HOST']);
    }
}

/*
 * zip
 * 压缩
 * @param string $zipName 压缩名称 例: test.zip
 * @param string $zipPath 压缩路径 例: /Uploads/test/ 或 /Uploads/test/test.txt
 * @module zip('../Uploads/Export/html/0/test.zip', '../Uploads/Export/html/0/');
 *                生成的压缩文件名（且直接保存在相应目录）, 要压缩的目录
 */
function zip($zipName, $zipPath) {

    import("@.ORG.Util.Pclzip");
    $zip = new PclZip($zipName);

    $zip->add($zipPath, PCLZIP_OPT_REMOVE_PATH, $zipPath);

    if ($res == 0) {
        return $zip->errorInfo(true);
    }

    return 1;
}

/*
 * copyFun
 * 复制
 * @param string $copyFrom 复制文件源路径
 * @param string $copyTo 复制到路径
 * @module copyFun('../Uploads/Export/html/0/', '../Uploads/Export/html/1/');
 *
 */
function copyFun($copyFrom, $copyTo) {

    $dir = opendir($copyFrom);

    if (!$dir) {
        return '目录打开不成功';
    }

    @mkdir($copyTo);
    while(false !== ( $file = readdir($dir))) {

        if (($file != '.') && ($file != '..') && ($file != '.svn')) {
            if (is_dir($copyFrom . '/' . $file)) {
                copyFun($copyFrom . '/' . $file, $copyTo . '/' . $file);
            } else {
                copy($copyFrom . '/' . $file, $copyTo . '/' . $file);
            }
        }
    }
    closedir($dir);

    return 1;
}


// 自动毕业
function autoGraduate($s_id) {

    // 读取学校缓存数据
    $school = loadCache('school');

    // 判断缓存学校表的字段
    if (time() > intval($school[$s_id]['s_next_graduate_time'])) {

        if (intval($school[$s_id]['s_is_graduating']) == 0) {

             // 把正在毕业字段置为1，并清除缓存
            M('School')->where(array('s_id' => $s_id))->save(array('s_is_graduating' => 1));
            @unlink(DATA_PATH . '~school.php');

            // 调用自动毕业方法
            D('CronTab')->autoGraduate($s_id);
            cacheData();
            return 1;
        } else {
            return 0;
        }
    } else {
        return 1;
    }
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
 * 获取资源配置
 * $type = 0 获取AuthResource表信息
 * $type = 1 获取Resource表信息
 */
function getResourceConfigInfo($type = 0) {
    $resource = C('RESOURCE_CONFIG');
    return $resource[$type];
}

/*
 * 把科目、版本、学制、年级、学期转换为中文，并写入标签表
 * $data 为课程数组
 * $table 为表名
 */
function turnIdToWord($data, $table) {

    // 动态识别表明和字段
    if ($table == 'TopicTerm') {
        $id = 'tt_id';
        $title = 'tt_title';
        $count = 'tt_count';
        $created = 'tt_created';
        $updated = 'tt_updated';
    } else {
        $id = 'rta_id';
        $title = 'rta_title';
        $count = 'rta_count';
        $created = 'rta_created';
        $updated = 'rta_updated';
    }

    $arr = array();

    // 在转换前，还需判断下学科等是否为0，是的话就是自定义课程了，需要把相关的标签写进题库和题库关系表
    if ($data['co_subject'] == 0) {

        // 查询term和term_relation表
        $teId = M('TermRelation')->where(array('object_id' => $data['co_id']))->getField('te_id', TRUE);
        $arr = M('Term')->where(array('te_id' => array('in', $teId)))->getField('te_title', TRUE);
    } else {
        $grade = C('GRADE_TYPE');
        $arr[] = getTypeNameById($data['co_subject'], 'COURSE_TYPE');
        $arr[] = getTypeNameById($data['co_version'], 'VERSION_TYPE');
        $arr[] = getTypeNameById($data['co_type'], 'SCHOOL_TYPE');
        $arr[] = $grade[1][$data['co_grade']];
        $arr[] = getTypeNameById($data['co_semester'], 'SEMESTER_TYPE');
    }

    $returnTermId = array();
    $topicTerm = M($table);

    // 整理参数
    foreach ($arr as $key => $value) {
        $topic = array();
        $topic[$title] = $value;

        // 对于新添加的标签，事先要到标签库里查询一下，如果有的话，就在使用量+1，否则就直接添加
        $ttId = $topicTerm->where($topic)->getField($id);
        if ($ttId) {
            $returnTermId[] = $ttId;
            $topic[$id] = $ttId;
            $save[$updated] = time();
            $save[$count] = array('exp', $count . '+1');
            $topicTerm->where($topic)->save($save);
        } else {
            $topic[$created] = time();
            $topic[$count] = 1;

            // 添加之后要把相应的id给存储起来
            $returnTermId[] = $topicTerm->add($topic);
        }
    }
    return $returnTermId;
}

// 获取活动相关的附件列表
function listAttachments($ids) {

    // 查询我的资源表
    $authResource = M('AuthResource')->where(array('ar_id' => array('IN', trim($ids, ','))))->select();

    $list = array();

    // 循环我的资源，列出每个资源的名称
    foreach ($authResource as $key => $value) {
        $list[$value['ar_id']]['ar_title'] = $value['ar_title'] . '.' . $value['ar_ext'];
        $list[$value['ar_id']]['ar_img'] = getResourceImg($value, 0);
        $list[$value['ar_id']]['ar_is_transform'] = $value['ar_is_transform'];
    }

    return $list;

}

// 将word文档转为pdf格式
function word2pdf($file_path, $save_path='', $prefix='ff0_'){

    // 文件不存在返回false
    if(!file_exists($file_path)) {
        return false;
    }

    // 设置转码后的文档名称
    // 如果是windows系统
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
        $osm = new COM("com.sun.star.ServiceManager")     or die ("Please be sure that OpenOffice.org is installed.\n");
        $args = array(MakePropertyValue("Hidden",true,$osm));
        $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
        $oWriterDoc = $oDesktop->loadComponentFromURL($file_path,"_blank", 0, $args);
        $export_args = array(MakePropertyValue("FilterName", "writer_pdf_Export", $osm));
        $oWriterDoc->storeToURL($save_path, $export_args);
        $oWriterDoc->close(true);
    }

    // 否则就是linux系统
    else {
        $word_2_pdf_bin = C('OPEN_OFFICE_SERVER');
        if(file_exists($save_path)) {
            return $save_path;
        }
        $cmd = $word_2_pdf_bin." -o $save_path -f pdf ".$file_path;
        exec($cmd, $out, $statue);

    if(file_exists($save_path)) {
            return $save_path;
        } else {
            return false;
        }
    }
}

function MakePropertyValue($name,$value,$osm){
    set_time_limit(0);
    $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
    $oStruct->Name = $name;
    $oStruct->Value = $value;
    return $oStruct;
}

// 将pdf转换为swf
function pdf2Swf($file_path, $save_path='', $prefix=''){

    // 文件不存在返回false
    if(!file_exists($file_path)) {
        return false;
    }

    $swf_bin = C('SWF_PATH');
    if (!file_exists($swf_bin)) {
        return false;
    }

    $cmd=$swf_bin.' -t '.$file_path.' -o '.$save_path.' -s flashversion=9';
    exec($cmd, $out, $statue);
    if(file_exists($save_path)) {
        return $save_path;
    } else {
        return false;
    }

}

// 获取转码音视频的详细信息
function getTranscodeParam($data) {

    // 读取配置
    $config = C('MP4_PARAM');

    foreach ($data as $key => &$value) {
        $value['rt_video_code'] = $config['video']['param']['videoCode']['param'][$value['rt_video_code']];
        $value['rt_video_rp'] = $config['video']['param']['resolvingPower']['param'][$value['rt_video_rp']];
        $value['rt_video_bite'] = $config['video']['param']['bite']['param'][$value['rt_video_bite']];
        $value['rt_video_frame'] = $config['video']['param']['frameRate']['param'][$value['rt_video_frame']];
        $value['rt_voice_code'] = $config['voice']['param']['voiceCode']['param'][$value['rt_voice_code']];
        $value['rt_voice_sample'] = $config['voice']['param']['sample']['param'][$value['rt_voice_sample']];
        $value['rt_voice_sound'] = $config['voice']['param']['sound']['param'][$value['rt_voice_sound']];
        $value['rt_voice_bite'] = $config['voice']['param']['bite']['param'][$value['rt_voice_bite']];
        $value['rt_cover_rp'] = $config['cover']['param']['resolvingPower']['param'][$value['rt_cover_rp']];
        $value['rt_cover_time'] = $config['cover']['param']['time']['param'][$value['rt_cover_time']];
    }

    return $data;
}

// 转mp4
function videoToMp4($file_path, $save_path='', $template){

    // 如果文件不存在，直接返回false
    if(!file_exists($file_path)) {
        return false;
    }

    // 转换后的文件
    $basename = substr($save_path . basename($file_path), 0 , strrpos($save_path . basename($file_path), '.'));
    $mp4_file_name = $basename . ".mp4";
    $mp4_jpg_name = $basename . "_s.jpg";
    $mp4_jpg_name2 = $basename . "_m.jpg";

    // 加载不同操作系统下转码工具
    $ffmpeg_bin = C('FFMPEG_BIN_PATH');

    // 如果工具不存在直接退出
    if(!file_exists($ffmpeg_bin))  return FALSE;

    // 如果已经有了转码后的文件退出
    if(file_exists($mp4_file_name)) return TRUE;

    // 配置参数
    $cmd = $ffmpeg_bin . " -threads 4 -n -i " . $file_path . " -ab " . $template['rt_voice_bite'] . " -ar " . $template['rt_voice_sample'] . " -vcodec " . $template['rt_video_code'] . " -qscale 6 -r " . $template['rt_video_frame'] . " -s " . $template['rt_video_rp'] . " -flags +loop -crf 24 -bt " . $template['rt_video_bite'] . " -vol 200 -vf yadif " . $mp4_file_name;
    $cmd2 = $ffmpeg_bin." -i " . $file_path." -y -f image2 -ss 25 -t 0.001 -s " . $template['rt_cover_rp'] . " " . $mp4_jpg_name;
    $cmd3 = $ffmpeg_bin." -i " . $file_path." -y -f image2 -ss 25 -t 0.001 -s " . $template['rt_cover_rp'] . " " . $mp4_jpg_name2;

     // 转码
    exec($cmd,$out, $statue);
    exec($cmd2,$out, $statue);
    exec($cmd3,$out, $statue);

    // 如果文件存在，返回true，否则返回false
    if(file_exists($mp4_file_name)) {
        return $mp4_file_name;
    } else {
        return FALSE;
    }
}

// 重组文件名称
function getFileName($file, $ext) {
    return substr($file, 0, strrpos($file, '.')) . '.' . $ext;
}

/*
 * trans
 * 自动转码
 * $type     资源的类型，0为用户资源，1为所有资源
 * $ids      转码的资源id，数组
 * $template 模板信息，数组，必须为二维
 */
function trans($type = 0, $ids = array(), $template = array()) {

    // 获取转码的信息
    $res = getResourceConfigInfo($type);

    // 依据类型获取是那个表的字段
    if ($type == 0) {
        $trans = 'ar_is_transform';
        $id = 'ar_id';
        $savename = 'ar_savename';
        $created = 'ar_created';
        $ext = 'ar_ext';
    } else {
        $trans = 're_is_transform';
        $id = 're_id';
        $savename = 're_savename';
        $created = 're_created';
        $ext = 're_ext';
    }

    // 搜索条件
    $where['m_id'] = array('IN', '2,4');
    $where[$trans] = 0;

    // 如果传ids
    if ($ids) {
        $where[$id] = array('IN', $ids);
    }

    // 查询
    $transData = M($res['TableName'])->where($where)->limit(0, 5)->select();

    // 如果没有要转码的资源，便退出
    if (!$transData) {
        return;
    }

    // 模板信息
    if (!$template) {
        $template = setArrayByField(M('ResourceTranscode')->where(array('rt_status' => 1))->select(), 's_id');
        $template = getTranscodeParam(setArrayByField($template, 's_id'));
    }

    // 把要转码的资源更新为2，说明此资源正在转码
    $save[$trans] = 2;
    M($res['TableName'])->where(array($id => array('IN', getValueByField($transData, $id))))->save($save);
    unset($save);

    // 获取允许的文件类型
    $allowType = C('ALLOW_FILE_TYPE');

    // 获取视频音频和文档允许转换类型
    $videoList = C('ALLOW_VIDEO_CONVERT');
    $docList = C('ALLOW_DOC_CONVERT');

    // 获取文档的类型
    $docType = $allowType['document'];

    // 保存错误和成功id
    $error = array();
    $success = array();

    $model = reloadCache('model');
    $model = setArrayByField($model, 'm_id');

    // 循环资源数组，依次转码
    foreach ($transData as $key => $value) {

        // 原始完整路径
        $filePath = $res['Path'][0] . $model[$value['m_id']]['m_name'] . '/' . date(C('RESOURCE_SAVE_RULES'), $value[$created]) . '/' . $value[$savename];

        $dir = $res['Path'][1] . $model[$value['m_id']]['m_name'] . '/';

        if (!is_dir($dir)) {
            @mkdir($dir, 0755);
        }

        $dir .= date(C('RESOURCE_SAVE_RULES'), $value[$created]) . '/';

        if (!is_dir($dir)) {
            @mkdir($dir, 0755);
        }

        // 判断要转码的资源是文档还是音视频
        if (in_array(strtolower($value[$ext]), $docType)) {

            $fileName = word2pdf($filePath, getFileName($dir . $value[$savename], 'pdf'));

            // 如果转码失败，把错误的ID给存储起来
            if ($fileName === FALSE) {
                $error[] = $value[$id];
            }

            // 把转好的pdf继续转成swf
            if (FALSE !== pdf2Swf($fileName, getFileName($fileName, 'swf'))) {
                $success[] = $value[$id];

                // 删除pdf文件，把原文件移到转码后的目录以便后续下载工作再删除原文件，更新资源转码状态为1
                //@unlink($fileName);
                rename($filePath, dirname($fileName) . '/' . $value[$savename]);
                @unlink($filePath);
                M($res['TableName'])->where(array($id => $value[$id]))->save(array($trans => 1));
            } else {
                $error_id[] = $re_id;
            }
        } else {

            // 转码
            $sample = $template[$value['s_id']] ? $template[$value['s_id']] : $template[0];

            $mp4Name = videoToMp4($filePath, $dir, $sample);

            if (FALSE !== $mp4Name) {
                $success[] = $value[$id];

                // 删除原资源，更新后缀和文件名
                unlink($filePath);
                M($res['TableName'])->where(array($id => $value[$id]))->save(array($trans => 1, $ext => 'mp4', $savename => basename($mp4Name)));

            } else {
                $error_id[] = $re_id;
            }
        }
    }

    // 有错误的id,便把其恢复成0
    if ($error) {
        $save[$trans] = 3;
        M($res['TableName'])->where(array($id => array('IN', $error)))->save($save);
    }

    // 回调
    trans($type, $ids, $template);

}

// 获取资源图片
function getResourceImg($res, $type = 0, $size = '100') {
    $config = getResourceConfigInfo($type);

    if ($type == 0) {
        $trans = 'ar_is_transform';
        $id = 'ar_id';
        $savename = 'ar_savename';
        $created = 'ar_created';
        $ext = 'ar_ext';
    } else {
        $trans = 're_is_transform';
        $id = 're_id';
        $savename = 're_savename';
        $created = 're_created';
        $ext = 're_ext';
    }

    $model = loadCache('model');
    $model = setArrayByField($model, 'm_id');

    $result = $config['Path'][$res[$trans]] . $model[$res['m_id']]['m_name'] . '/';

    if ($res['m_id'] == 1) {
        if ($size) {
            $size = $size . '/';
        }
        $result .= date(C('RESOURCE_SAVE_RULES'), $res[$created]) . '/' . $size . $res[$savename];
    }

    if ($res['m_id'] == 2) {
        $result .= date(C('RESOURCE_SAVE_RULES'), $res[$created]) . '/' . substr($res[$savename], 0, strpos($res[$savename], '.')) . '_s.jpg';
    }

    if (is_dir($result) || !file_exists($result)) {
        $result = C('RESOURCE_DEFAULT_IMG') . $res[$ext] . '.png';
    }

    if (!file_exists($result)) {
        $result = C('RESOURCE_DEFAULT_IMG') . 'default.jpg';
    }

    return turnTpl($result);
}

?>