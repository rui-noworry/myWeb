<?php

class CronTabAction extends BaseAction {

    // 每小时执行
    public function hourly() {

    }

    // 每天执行
    public function daily() {

        // 依据下载次数奖励积分
        resourceDownloadAward();

        // 自动转码
        trans();
        trans(1);
    }

    // 每周执行
    public function weekly() {
        D('CronTab')->schoolSystem();
    }

    // 每个月执行
    public function monthly() {

    }

}