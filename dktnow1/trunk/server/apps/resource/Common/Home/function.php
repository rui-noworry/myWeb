<?php

function myApps($c_id, $applications, $is_manager) {

    $apps = C('APPLICATION_LISTS');

    if ($c_id) {

        // 若已经加入班级，则返回我的应用列表
        if ($applications) {

            $myApps = explode(',', $applications);
            foreach ($myApps as $v) {
                $return[] = $apps[$v];
            }
        }

    } else {
        // 若还没加入班级
        $return = $apps;
        unset($return[99]);
    }

    if ($is_manager) {
        $return[] = $apps[99];
    }
    return $return;
}

function doRequest($url, $method='GET') {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_COOKIESESSION,true);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
}

//    时间的格式化
function timeFormat($date) {

    $now = time(); //获取现在的时间戳
    $senconds = intval($now) - intval($date);

    if ($senconds < 60){
        return $senconds.'秒前'; //获取秒
    }

    $mins = floor($senconds/60);
    if ($mins < 60){
        return $mins.'分钟前'; // 获取分钟
    }

    $hours = floor($mins/60);

    if ($hours < 24){
        return $hours.'小时前'; //获取小时
    }

    $days = floor($hours/24);

    if ($days < 7){
        return $days.'天前'; //获取天
    }

    $times = date('Y-m-d H:m:s', $date);

    return $times;
}
?>