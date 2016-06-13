<?php
/**
 * SchoolAction
 * 我的首页模块
 *
 * 创建时间: 2013-6-3
 *
 */
class SchoolAction extends BaseAction{

    public function index() {

        if (!$this->authInfo['s_id']) {
            $this->redirect('/MyResource');
        }

        // 资源栏目
        $cate = M('ResourceCategory')->where(array('s_id' => $this->authInfo['s_id'], 'rc_is_show' => 1))->field('rc_id,rc_title,rc_num')->select();
        $cate = setArrayByField($cate, 'rc_id');

        $where['s_id'] = $this->authInfo['s_id'];
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

        $this->total = intval(M('Resource')->count());
        $this->model = reloadCache('model');
        $this->cate = $cate;
        $this->display();
    }
}
?>