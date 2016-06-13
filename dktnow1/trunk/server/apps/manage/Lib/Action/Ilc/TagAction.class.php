<?php
/**
 * TagAction
 * 知识点管理模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-5-20
 *
 */
class TagAction extends CommonAction{

    public function _initialize() {

        parent::_initialize();

        // 获取所有学科
        $this->t_subject = C('COURSE_TYPE');
    }

    public function index(){

        $this->choose_subject = intval($_POST['t_subject']);

        parent::index();
    }


    // 获取标签的pid
    public function _filter(&$map) {

        // 若没有搜索条件和父节点的时候，调用所有父类数据
        if (empty($_POST['search']) && !isset($map['t_pid']) ) {

            // 令 t_pid = 0
            $map['t_pid'] = intval($map['t_pid']);
        }

        if ($map['t_name']){
            $map['t_name'] = array('like', '%'.$map['t_name'].'%');
        }

        // 获取上级节点
        if (isset($map['t_pid'])) {

            if ($res = M('Tag')->find($map['t_pid'])) {

                $this->assign('t_level', $res['t_level']+1);

            } else {
                $this->assign('t_level', 1);
            }
        }
    }

    public function _before_add(){

        // 查找数据
        $tag = M('Tag')->find(intval($_REQUEST['t_pid']));

        // 页面赋值
        $this->assign('t_pid', intval($tag['t_id']));
        $this->assign('t_level', intval($tag['t_level']+1));
    }

    // 删除知识点
    public function del() {

        $t_id = $_REQUEST['id'];

        if (!$t_id) {
            $this->error('参数错误');
        }

        // 条件 删除该节点和所有子节点
        $where = 't_id IN('.$t_id.') OR t_pid IN('.$t_id.')';

        $res = M('Tag')->where($where)->delete();

        $tagRelattionCount = M('TagRelation')->where(array('t_id' => array('IN', $t_id)))->count();

        if ($tagRelattionCount) {
            M('TagRelation')->where(array('t_id' => array('IN', $t_id)))->delete();
        }

        if ($res) {
            $this->success('删除成功');
        }
    }

}
?>