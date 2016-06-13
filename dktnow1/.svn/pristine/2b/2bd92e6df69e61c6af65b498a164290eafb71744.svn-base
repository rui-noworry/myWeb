<?php
/**
 * ResourceNodeAction
 * 视频时间点笔记接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-4
 *
 */
class ResourceNoteAction extends OpenAction {

    // 添加时间点笔记(学生)
    public function saveNote() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($re_id) || !strval($rn_time_point) || !strval($rn_content)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 检测是否为学生,视频点长度大于8
        if ($this->auth['a_type'] != 1 || strlen($rn_time_point) != 8) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 检测资源是否存在，是否已转码，是否为视频
        $resource = M('Resource')->where(array('re_id' => $re_id))->field('re_id,re_is_transform,m_id')->find();
        if (!$resource || $resource['re_is_transform'] != 1 || $resource['m_id'] != 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 还需查询时间点是否重复
        $flag = M('ResourceNote')->where(array('rn_time_point' => $rn_time_point))->getField('rn_time_point');
        if ($flag) {
            $this->ajaxReturn(array('status' => 0, 'info' => '写入失败'));
        }

        $data['a_id'] = $a_id;
        $data['s_id'] = $this->auth['s_id'];
        $data['re_id'] = $re_id;
        $data['rn_time_point'] = $rn_time_point;
        $data['rn_content'] = $rn_content;
        $data['rn_created'] = time();
        $result = M('ResourceNote')->add($data);
        if ($result) {
            $this->ajaxReturn(array('status' => 1, 'info' => '写入成功'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '写入失败'));
        }
    }

    // 获取时间点笔记列表(学生)
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($re_id) || !intval($page_size)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 检测是否为学生
        if ($this->auth['a_type'] != 1) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 检测资源是否存在，是否已转码，是否为视频
        $resource = M('Resource')->where(array('re_id' => $re_id))->field('re_id,re_is_transform,m_id')->find();
        if (!$resource || $resource['re_is_transform'] != 1 || $resource['m_id'] != 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 获取列表
        $list = getListByPage('ResourceNote', 'rn_id DESC',array('re_id' => $re_id), $page_size, 0, $page ? $page : 1);
        if ($list) {
            $list['page'] = $this->transPage($list['page'], 5);
            $this->ajaxReturn(array('status' => 1, 'info' => $list));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '无数据'));
        }
    }

    // 更新
    public function update() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($rn_id) || !strval($rn_content)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 检测是否为学生
        if ($this->auth['a_type'] != 1) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 检测笔记是否存在
        $note = M('ResourceNote')->where(array('a_id' => $a_id, 'rn_id' => $rn_id))->getField('rn_id');
        if (!$note) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 更新
        $where['rn_id'] = $rn_id;
        $save['rn_content'] = $rn_content;
        $result = M('ResourceNote')->where($where)->save($save);
        if ($result) {
            $this->ajaxReturn(array('status' => 1, 'info' => '更新成功'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '更新失败'));
        }
    }

    // 删除视频笔记
    public function delete() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($rn_id)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 检测是否为学生
        if ($this->auth['a_type'] != 1) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 检测笔记是否存在
        $note = M('ResourceNote')->where(array('a_id' => $a_id, 'rn_id' => $rn_id))->getField('rn_id');
        if (!$note) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 删除
        $where['rn_id'] = $rn_id;
        $result = M('ResourceNote')->where($where)->delete();
        if ($result) {
            $this->ajaxReturn(array('status' => 1, 'info' => '删除成功'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '删除失败'));
        }
    }


    // 用正则转换分页信息
    public function transPage($page, $page_size) {

        // 如果page为空则返回默认值
        if(isset($page) && strlen($page) == 0 ) {
            return array(
                'totalPage'     => 0,
            );
        }

        // 用正则匹配标签内的值，得到相应的分页信息
        preg_match_all('/\d+/', strip_tags($page), $matches);

        // 长度为3，说明只有一页
        if(count($matches[0]) == 3) {
            $data['totalRows'] = $matches[0][count($matches[0])-1];
        } else {
            $data['totalRows'] = $matches[0][count($matches[0])-1];
        }
        return $data;
    }
}