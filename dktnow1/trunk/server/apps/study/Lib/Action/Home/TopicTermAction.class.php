<?php
/**
 * TopicTermAction
 * 题库表
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-18
 *
 */
class TopicTermAction extends BaseAction {

    // 添加题目标签
    public function insert() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 整理参数
        $_POST['tt_count'] = array('exp', 'tt_count+1');

        // 对于新添加的标签，事先要到标签库里查询一下，如果有的话，就在使用量+1，否则就直接添加
        $ttId = M('TopicTerm')->where(array('tt_title' => $_POST['tt_title']))->getField('tt_id');
        if ($ttId) {
            $this->updateData();
            $this->success($ttId);
        }

        $result = $this->insertData();
        if (!$result) {
            $this->error('操作失败');
        }
        $this->success($result);
    }

    // 更新
    public function update() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 接收参数
        $where['tt_id'] = intval($_POST['tt_id']);

        // 如果有flag参数，说明是删除，则使用次数减1
        if (intval($_POST['flag'])) {
            $data['tt_count'] = array('exp', 'tt_count-1');
        } else {
            $data['tt_count'] = array('exp', 'tt_count+1');
        }

        $data['tt_updated'] = time();

        $result = M('TopicTerm')->where($where)->save($data);
        if (!$result) {
            $this->error('操作失败');
        }
        $this->success('操作成功');
    }

    // 配置数据，把配置文件内的相关数据录入到题库表里作为初始数据
    public function config() {

        // 学段
        $data['school_type'] = C('SCHOOL_TYPE');

        // 年级，这里需要做个处理，因为是个二维数组，只需键为1的子数组即可
        $data['grade_type'] = C('GRADE_TYPE');

        // 学期
        $data['semester_type'] = C('SEMESTER_TYPE');

        // 学科
        $data['course_type'] = C('COURSE_TYPE');

        // 版本
        $data['version_type'] = C('VERSION_TYPE');

        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if ($key == 'grade_type') {
                    foreach ($v as $kk => $vv) {
                        if ($k == 1) {
                            $arr['tt_title'] = $vv;
                            $arr['tt_created'] = time();
                            //D('TopicTerm')->add($arr);
                        }
                    }
                    continue;
                }
                $arr['tt_created'] = time();
                $arr['tt_title'] = $v;
                //D('TopicTerm')->add($arr);
            }
        }
    }

}