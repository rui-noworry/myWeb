<?php
/**
 * IndexAction
 * 首页登陆模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-3
 *
 */
class IndexAction extends BaseAction{

    public function index() {

        // 判断是否自动登录
        /*if (isLogin()) {

            $this->redirect('/School');
            exit;
        }*/

        $cache = C('HTML_PATH') . 'index.shtml';

        // 缓存页面
        if (file_exists($cache)) {
            echo file_get_contents($cache);
            exit;
        }

        // 资源栏目
        $cate = M('ResourceCategory')->where(array('s_id' => 0, 'rc_is_show' => 1))->field('rc_id,rc_title,rc_num')->select();
        $cate = setArrayByField($cate, 'rc_id');

        $where['s_id'] = 0;
        $where['re_is_pass'] = 1;
        $where['re_is_transform'] = 1;
        $where['rc_id'] = array('IN', getValueByField($cate, 'rc_id'));
        $resource = M('Resource')->where($where)->select();

        $auth = getDataByArray('Auth', $resource, 'a_id', 'a_id,a_nickname');

        foreach ($resource as $key => $value) {

            $value['a_nickname'] = $value['a_id'] == 0 ? '' : $auth[$value['a_id']]['a_nickname'];
            $value['re_img'] = getResourceImg($value, 1);
            $cate[$value['rc_id']]['lists'][$key] = $value;
        }

        $this->contribution = M('Auth')->where(array('a_status' => 1, 'a_points' => array('gt', 0)))->order('a_points DESC')->limit(5)->field('a_id,a_nickname,a_points')->select();

        $this->model = reloadCache('model');
        // 赋值
        $this->cate = $cate;
        $this->autoHtml();
        $this->display();
    }
}
?>