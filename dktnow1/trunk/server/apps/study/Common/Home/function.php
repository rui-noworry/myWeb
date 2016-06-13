<?php

function myApps($c_id, $s_id, $applications, $is_manager) {

    $apps = C('APPLICATION_LISTS');
    foreach ($apps as $k => $v) {

        $apps[$k]['url'] = __APPURL__ . $v['url'];
    }
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
        $return[] = $apps[1];

        if ($s_id) {
            $return[] = $apps[2];
        }
        $return[] = $apps[4];
        $return[] = $apps[5];
    }

    if ($is_manager) {
        $return[] = $apps[99];
    }
    return $return;
}

function turnHtmlTag($content) {
     return urlencode($content);
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

/*
 * generationImg
 * 生成图片
 * @param string $path 生成路径
 * @param int $id ID
 * @param string $title 标题
 * @param string $content 内容
 * @param int $width 生成的宽度
 *
 * @return bool $result
 */
function generationImg($path, $id, $title, $content, $width = 590) {

    // 配置活动静态页面地址
    $htmlPath = $path . "Html/" . $id . ".html";

    // 配置生成的活动图片路径
    $imagePath = $path . 'Image/' . $id . '.png';

    // 组织内容数据
    $content = stripslashes($content);
    $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><title>'.$title.'</title><meta content="text/html; charset=utf-8" http-equiv="Content-Type"><style>#wrap{width:' . $width . 'px;float:left;overflow:hidden;}*{margin:0px;padding:0px;font-family:"微软雅黑,宋体,Microsoft Yahei,Arial";}</style></head><body><div id="wrap">';
    $html .= $content;
    $html .= '</div></body></html>';

    // 写文件
    file_put_contents($htmlPath, $html);

    // 生成图片
    $wkhtmltoimage = C('WKHTMLTOIMAGE_PATH') . ' --quality 0 ' . $htmlPath . " " . $imagePath;
    exec($wkhtmltoimage, $output, $status);

    // 载入图像类
    import("@.ORG.Util.Image");
    $image = new Image();

    // 裁剪图片
    $width = $width + 10;
    $image->clippingThumb($imagePath, $imagePath, 0, $width, 0, 0, $width);
}
?>