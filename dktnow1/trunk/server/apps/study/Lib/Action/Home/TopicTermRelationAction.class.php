<?php
/**
 * TopicTermRelationAction
 * 题目标签关系类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-18
 *
 */
class TopicTermRelationAction extends BaseAction {

    public function insert() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        $this->insertData();
    }

}