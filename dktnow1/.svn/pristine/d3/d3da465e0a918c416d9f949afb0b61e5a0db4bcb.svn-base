<?php
/**
 * TermRelationAction
 * 课程标签关系类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-14
 *
 */
class TermRelationAction extends BaseAction {

    // 写入数据
    public function insert() {

        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作!');
        }

        $this->insertData();
    }
}