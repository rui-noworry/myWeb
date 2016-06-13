<?php
/**
 * CronTabAction
 * 自动执行删除模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-6-3
 *
 */
class CronTabAction extends BaseAction{

    // 每小时执行
    public function hourly() {

    }

    // 每天执行
    public function daily() {
        D('CronTab')->autoDelClasshour();
    }

    // 每周执行
    public function weekly() {
        D('CronTab')->schoolSystem();
    }

    // 每个月执行
    public function monthly() {

    }
}
?>