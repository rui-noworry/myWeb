<?php
/**
 * SchoolSearchAction
 * 学校资源搜索模块
 *
 * 作者:  肖连义
 * 创建时间: 2013-6-3
 *
 */
class SchoolSearchAction extends BaseAction{

    public function index() {

        if (!$this->authInfo['s_id']) {
            $this->redirect('/MyResource');
        }

        $rc = intval($_REQUEST['rc']);
        $keywords = $_REQUEST['keywords'];
        $resFrom = intval($_REQUEST['resFrom']);
        $resType = intval($_REQUEST['resType']);

        $where['re_is_pass'] = 1;
        $where['re_is_transform'] = 1;
        $where['s_id'] = array(array('eq', $this->authInfo['s_id']), array('eq', 0), 'OR');

        if ($keywords) {
            $where['re_title'] = array('LIKE', '%'.$keywords.'%');
        }

        if ($resFrom) {
            $where['re_from'] = $resFrom - 1;
        }

        if ($resType) {
            $where['m_id'] = $resType;
        }

        if ($rc) {
            $txt = M('ResourceCategory')->where(array('rc_id' => $rc, 's_id' => $this->authInfo['s_id']))->getField('rc_title');

            if (!$txt) {
                $this->error('未找到符合的数据');
            }

            $where['rc_id'] = $rc;
        }

        $res = getListByPage('Resource', 're_id DESC', $where, 30, 0, intval($_GET['p']));

        if ($res) {
            $auth = getDataByArray('Auth', $res['list'], 'a_id', 'a_id,a_nickname');

            foreach ($res['list'] as $key => $value) {
                $res['list'][$key]['a_nickname'] = $value['a_id'] == 0 ? '' : $auth[$value['a_id']]['a_nickname'];
                $res['list'][$key]['re_img'] = getResourceImg($value, 1);
            }
        }

        // 赋值
        $this->model = reloadCache('model');
        $this->keywords = $keywords;
        $this->res = $res;
        $this->txt = $txt;
        $this->display();
    }
}
?>