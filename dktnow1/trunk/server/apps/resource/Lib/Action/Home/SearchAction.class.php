<?php
/**
 * SearchAction
 * 搜索模块
 *
 * 作者:  肖连义
 * 创建时间: 2013-6-3
 *
 */
class SearchAction extends BaseAction{

    public function index() {

        $or = intval($_REQUEST['order']);
        $rc = intval($_REQUEST['rc']);
        $keywords = $_REQUEST['keywords'];
        $resFrom = intval($_REQUEST['resFrom']);
        $resType = intval($_REQUEST['resType']);

        $order = 're_id DESC';

        $where['re_is_pass'] = 1;
        $where['re_is_transform'] = 1;
        $where['s_id'] = 0;

        if ($keywords) {
            $where['re_title'] = array('LIKE', '%'.$keywords.'%');
        }

        if ($resFrom) {
            $where['re_from'] = $resFrom - 1;
        }

        if ($resType) {
            $where['m_id'] = $resType;
        }

        if ($or) {
            $orderArr = array('', '最新资源', '最热资源', '推荐资源');
            $orArr = array('', 're_id DESC', 're_hits DESC', 're_id DESC');
            $order = $orArr[$or];

            if ($or == 3) {
                $where['re_recommend'] = 1;
            }
            $txt = $orderArr[$or];
        }

        if ($rc) {
            $txt = M('ResourceCategory')->where(array('rc_id' => $rc, 's_id' => 0, 'rc_is_show' => 1))->getField('rc_title');

            if (!$txt) {
                $this->error('未找到符合的数据');
            }
            $where['rc_id'] = $rc;
        }

        $res = getListByPage('Resource', $order, $where, 30, 0, intval($_GET['p']));

        if ($res) {
            $auth = getDataByArray('Auth', $res['list'], 'a_id', 'a_id,a_nickname');

            foreach ($res['list'] as $key => $value) {
                $res['list'][$key]['a_nickname'] = $value['a_id'] == 0 ? '' : $auth[$value['a_id']]['a_nickname'];
                $res['list'][$key]['re_img'] = getResourceImg($value, 1);
            }
        }

        $this->contribution = M('Auth')->where(array('a_status' => 1, 'a_points' => array('gt', 0)))->order('a_points DESC')->limit(5)->field('a_id,a_nickname,a_points')->select();

        // 赋值
        $this->model = reloadCache('model');
        $this->keywords = $keywords;
        $this->res = $res;
        $this->txt = $txt;
        $this->display();
    }
}
?>