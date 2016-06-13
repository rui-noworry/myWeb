<?php
/**
 * MyResourceAction
 * 我的资源模块
 *
 * 创建时间: 2013-6-3
 *
 */
class MyResourceAction extends BaseAction{

    public function index() {

        $this->model = reloadCache('model');

        // 查询我的班级和群组
        $this->authInfo = D('MyResource')->getAuthInfo($this->authInfo);

        // 班级
        if ($this->authInfo['c_id']) {
            $class = M('Class')->where(array('c_id'=> array('IN', $this->authInfo['c_id'])))->field('c_id,s_id,c_type,c_grade,c_title,c_is_graduation')->select();
            foreach ($class as $key => &$value) {
                $value['c_name'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation']);
            }
            $this->class = $class;
        }

        // 群组
        if ($this->authInfo['cro_id']) {
            $crowd = M('Crowd')->where(array('cro_id'=> array('IN', $this->authInfo['cro_id'])))->field('cro_id,cro_title')->select();
            $this->crowd = $crowd;
        }

        $this->display();
    }

    // 异步显示班级下的成员
    public function searchMember() {

        // 检测
        if (!intval($_POST['c_id']) && !intval($_POST['cro_id'])) {
            $this->error('非法操作');
        }

        // 查询班级下的学生ID
        if ($_POST['c_id']) {
            $where['s_id'] = $this->authInfo['s_id'];
            $where['c_id'] = intval($_POST['c_id']);
            $table = 'ClassStudent';
        }

        // 查询群组下的学生ID
        if ($_POST['cro_id']) {
            $where['s_id'] = $this->authInfo['s_id'];
            $where['cro_id'] = intval($_POST['cro_id']);
            $table = 'AuthCrowd';
        }

        // 依据学生ID查询学生姓名
        $students = M($table)->where($where)->getField('a_id', TRUE);
        $studentArr = M('Auth')->where(array('a_id' => array('IN', $students)))->field('a_nickname,a_id')->select();

        // 过滤掉自己
        foreach ($studentArr as $key => $value) {
            if ($this->authInfo['a_id'] == $value['a_id']) {
                unset($studentArr[$key]);
            }
        }
        sort($studentArr);

        echo json_encode($studentArr);
    }

    // 显示我的资源列表
    public function lists() {

        // 类型
        $type = intval($_POST['type']);

        // 我的资源
        if ($type == 0) {
            $table = 'AuthResource';
            $order = 'ar_id DESC';
            $time = 'ar_created';

        // 我发布的资源
        } elseif ($type == 1) {
            $table = 'Resource';
            $order = 're_id DESC';
            $time = 're_created';

        // 我分享的资源
        } elseif ($type == 2) {
            $where['ar_is_shared'] = 1;
            $table = 'AuthResource';
            $order = 'ar_id DESC';
            $time = 'ar_created';
            $type = 0;

        // 分享给我的资源
        } elseif ($type == 3) {
            $ar_id = getListByPage('ResourceShare', 'ar_id DESC', array('rs_a_id' => $this->authInfo['a_id']), 12, 1, intval($_POST['p']));
            $ar_id = getValueByField($ar_id['list'], 'ar_id');
            $where['ar_id'] = array('IN', $ar_id);
            $table = 'AuthResource';
            $order = 'ar_id DESC';
            $time = 'ar_created';
            $flag = TRUE;
            $type = 0;
        }

        if (!$flag) {
            $where['a_id'] = $this->authInfo['a_id'];
        }

        $res = getListByPage($table, $order, $where, 12, 1, intval($_POST['p']));
        foreach ($res['list'] as $key => $value) {
            $res['list'][$key]['a_nickname'] = $this->authInfo['a_nickname'];
            $res['list'][$key]['re_img'] = getResourceImg($value, $type);
            $res['list'][$key]['time'] = date('Y-m-d', $value[$time]);
        }

        echo json_encode($res);
    }

    // 删除
    public function del() {

        // 删除时，还得把分享的ID给去掉
        $where['ar_id'] = array('IN', strval($_POST['id']));
        $where['a_id'] = $this->authInfo['a_id'];
        $result =  M('AuthResource')->where($where)->delete();
        if ($result) {
            M('ResourceShare')->where($where)->delete();
            echo 1;
        } else {
            echo 0;
        }
    }

    // 下载
    public function download() {

        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->redirect('/MyResource');
        }

        // 验证
        $res = M('Resource')->where(array('re_id' => $id))->find();

        if (!$res) {
            $this->redirect('/MyResource');
        }

        if ($res['a_id'] != $this->authInfo['a_id']) {
            if ($this->authInfo['a_points'] < $res['re_download_points']) {
                $this->error('积分不足，请先获取足够积分');
            }

            M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setDec('a_points', $res['re_download_points']);
            M('Auth')->where(array('a_id' => $res['a_id']))->setInc('a_points', $res['re_download_points']);
            M('Resource')->where(array('re_id' => $id))->setInc('re_downloads');
        }

        // 组织数据，准备下载
        $table = getResourceConfigInfo(1);
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        $path = $table['Path'][$res['re_is_transform']] . $model[$res['m_id']]['m_name'] . '/' . date(C('RESOURCE_SAVE_RULES'), $res['re_created']) . '/' . substr($res['re_savename'], 0, strrpos($res['re_savename'], '.')) . '.' . $res['re_ext'];

        $fileName = $res['re_title'];

        download($path, iconv("utf-8", "gb2312", $fileName), $res['re_ext'], false);
    }
}
?>