<?php
/**
 * ClientAction
 * 客户端
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-03-26
 *
 */
class ClientAction extends Action {

    public function _initialize(){

        $this->bannerOn = 4;
    }

    // 首页
    public function index() {
        $this->redirect('/Index');
    }

    // 下载
    public function download() {

        // 判断ANDRIOD教师端版本是否存在
        $adtd = C('CONFIG_TMP_PATH') . 'android_teacher_version.apk';
        if (!file_exists($adtd)) {
            $adtd = '';
        }

        $this->adtd = $adtd;

        // 判断ANDRIOD学生端版本是否存在
        $adsd = C('CONFIG_TMP_PATH') . 'android_student_version.apk';
        if (!file_exists($adsd)) {
            $adsd = '';
        }

        $this->adsd = $adsd;

        // 判断IOS教师端版本是否存在
        $iotd = C('CONFIG_TMP_PATH') . 'ios_teacher_version.ipa';
        if (!file_exists($iotd)) {
            $iotd = '';
        } else {
            $iotd = 'http://' . $_SERVER['HTTP_HOST'] . '/Client/iosPath';
        }

        $this->iotd = $iotd;

        // 判断IOS学生端版本是否存在
        $iosd = C('CONFIG_TMP_PATH') . 'ios_student_version.ipa';
        if (!file_exists($iosd)) {
            $iosd = '';
        } else {
            $iosd = 'http://' . $_SERVER['HTTP_HOST'] . '/Client/iosPath/status/1';
        }

        $this->iosd = $iosd;

        // 判断三星教师端版本是否存在
        $adst = C('CONFIG_TMP_PATH') . 'android_sx_teacher.apk';
        if (!file_exists($adst)) {
            $adst = '';
        }

        $this->adst = $adst;

        // 判断三星学生端版本是否存在
        $adss = C('CONFIG_TMP_PATH') . 'android_sx_student.apk';
        if (!file_exists($adss)) {
            $adss = '';
        }

        $this->adss = $adss;

        $this->display();
    }

    public function iosPath() {

        // 获取参数
        $status = intval($_GET['status']);

        // 版本
        $version = array('teacher', 'student');
        $language = array('教师', '学生');

        // 生成代码
        $str = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd"><plist version="1.0"><dict><key>items</key><array><dict><key>assets</key><array><dict><key>kind</key><string>software-package</string><key>url</key><string>http://' . $_SERVER['HTTP_HOST'] . '/apps/Uploads/Config/ios_' . $version[$status] . '_version.ipa</string></dict></array><key>metadata</key><dict><key>bundle-identifier</key><string>com.mink.mcute.dkt' . $version[$status] . '</string><key>bundle-version</key><string>1.0</string><key>kind</key><string>software</string><key>title</key><string>大课堂' . $language[$status] . '端</string></dict></dict></array></dict></plist>';

        echo $str;
    }
}
?>