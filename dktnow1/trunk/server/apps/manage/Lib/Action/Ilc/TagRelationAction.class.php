<?php
/**
 * TagRelationAction
 * 目录标签关系模块
 *
 * 作者: 黄蕊
 * 创建时间: 2012-5-22
 *
 */
class TagRelationAction extends CommonAction {

    public function index(){

        // 过滤条件
        $map = parent::_search();

        // 获取该目录下的知识点
        $data = M('TagRelation')->where($map)->select();

        if ($data){

            foreach ($data as $key => $value){
                $arrTagId[] = $value['t_id'];
            }
        }

        $condition['t_id']  = array('in', $arrTagId);

        parent::_list(M('Tag'), $condition);

        // 页面赋值
        $this->assign('d_id', $_REQUEST['d_id']);
        $this->display('index');
    }

    public function delete(){

        // 接收参数
        $t_id = strval($_REQUEST['t_id']);
        $d_id = strval($_REQUEST['d_id']);

        if ($t_id && $d_id) {

            // 条件
            $condition = array('t_id' => array('IN', $t_id), 'd_id' => $d_id);

            $result = M('TagRelation')->where($condition)->delete();

        } else {
           $result =  $this->error('非法操作');
        }

        if (false !== $result) {

            $jumpUrl = '/' . GROUP_NAME . '/' . $this->getActionName() . '/index/d_id/'.$d_id;

            //成功提示
            $this->assign('jumpUrl', $jumpUrl);
            $this->success('成功');

        } else {

            //失败提示
            $this->error('失败');
        }
    }

    public function _before_add(){
        $this->d_id = intval($_REQUEST['d_id']);
    }

    public function insert(){

        // 接收参数
        $tidArr = $_REQUEST['id'];
        $d_id = intval($_REQUEST['d_id']);

        if (!$d_id && !$tidArr) {
            $this->error('参数错误');
        }

        $data['d_id'] = $d_id;
        $temp = explode(',', $tidArr);

        $count = count($temp);

        // 循环插入数据
        for ($i = 0; $i < $count; $i ++) {

            $data['t_id']  = $temp[$i];
            M('TagRelation')->add($data);
        }

        $this->index();
    }

    public function getChildPoints(){

        // 接收参数
        $t_id = intval($_POST['t_id']);
        $d_id = intval($_POST['d_id']);

        if ($d_id) {

            $subject = M('Directory')->where(array('d_id' => $d_id))->getField('d_subject');

            if ($subject) {
                $map['t_subject'] = $subject;
            }
        }

        if ($t_id) {
            $map['t_pid'] = $t_id;
        }

        // 查询
        $result = M('Tag')->where($map)->select();

        // 返回结果
        echo json_encode($result);
    }

}

?>