<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
        if (isLogin()) {
            header("Location:apps/study");
        } else {
            C(loadCache('config'));
            reloadCache();
            cacheData();
            $this->display();
        }
    }
    public function test() {
        dump($_COOKIE);
    }
}