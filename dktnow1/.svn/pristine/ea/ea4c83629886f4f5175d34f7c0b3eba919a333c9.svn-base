<?php
/**
 * CrowdAction
 * 群组
 *
 * 作者:  赵鹏 (zhaop@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class CrowdAction extends BaseAction{

    public function index() {

        $this->assign('a_id', $this->authInfo['a_id']);
       $this->display();

    }

    // 分页查找群组
    public function lists() {

        // 排序方式
        $order = 'cro_id ASC';

        $where['a_id'] = $this->authInfo['a_id'];
        $where['s_id'] = $this->authInfo['s_id'];

        if (intval($_POST['type']) != 2) {
            if (intval($_POST['type'])) {
                $map['s_id'] = $this->authInfo['s_id'];
                $croId = M('AuthCrowd')->where($where)->getField('cro_id', TRUE);
                $map['cro_id'] = array('IN', implode(',', $croId));
            } else {
                $map = $where;
            }

            $result = getListByPage('Crowd', $order, $map, 5, 1, intval($_POST['p']));

            // 查询每个群组对应的课程
            $cro_id = M('Course')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('cro_id,co_id')->select();

            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['cro_logo'] = getCrowdLogo($value['cro_logo']);
                foreach ($cro_id as $k => $v) {
                    if ($v['cro_id'] && strpos($v['cro_id'], ',' . $value['cro_id'] . ',') !== FALSE) {
                        $result['list'][$key]['co_id'] = $v['co_id'];
                        break;
                    } else {
                        $result['list'][$key]['co_id'] = 0;
                    }
                }
            }
            sort($result['list']);
        }
        echo json_encode($result);
    }

    // 创建群组
    public function insert() {

        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];

        // 保存当前数据对象
        $model = D('Crowd');

        if (false === $model->create()) {
            $info = $this->error($model->getError());
        }

        //群组表中添加数据
        $show['status'] = intval($model->add());

        echo json_encode($show);
    }

    // 查找群组
    public function search() {

        $where['s_id'] = $this->authInfo['s_id'];

        if ($_POST['cro_title']) {
            $where['cro_title'] = array('LIKE', '%' . $_POST['cro_title'] . '%');
        }

        // 根据条件获取群组
        $list = M('Crowd')->where($where)->field('cro_id,cro_title,a_id')->select();

        // 若无数据
        if (!$list) {
            $result['info'] = "未找到群组！";
            $result['status'] = 0;

            echo json_encode($result);exit;
        }

        // 获取已经加入的群组
        $joinedList = M('AuthCrowd')->where(array('cro_id' => array('IN', getValueByField($list, 'cro_id')), 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('cro_id')->select();
        $joinedList = getValueByField($joinedList, 'cro_id');

        // 获取审核中的群组
        $checkList = getValueByField(M('CrowdCheck')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->Field('cro_id,cc_id')->select(), 'cro_id');

        // 整理数据，is_in为1说明a_id在群组内，为0说明a_id不在群组内
        foreach ($list as $key => $value) {

            if ($value['a_id'] == $this->authInfo['a_id'] || in_array($value['cro_id'], $joinedList) || in_array($value['cro_id'], $checkList)) {

                $list[$key]['is_in'] = 1;

            } else {

                $list[$key]['is_in'] = 0;
            }
        }

        // 返回数据
        $result['info'] = $list;
        $result['status'] = 1;

        echo json_encode($result);
    }

    // 进入群组空间
    public function space() {

        $id = intval($_GET['id']);

        if (!$id) {
            $this->redirect('/Index');
        }

        // 查询组信息
        $crowd = M('Crowd')->where(array('cro_id' => $id))->find();
        if (!$crowd) {
            $this->redirect('/Index');
        }

        D('Trend')->crowdTrend($id, $this->authInfo['s_id']);

        if ($crowd['a_id'] == $this->authInfo['a_id']) {
            $type = 1;
        }

        // 查询组成员ID
        $authId = M('AuthCrowd')->where(array('cro_id' => $id, 'a_id' => array('neq', $this->authInfo['a_id'])))->select();

        // 查询用户信息
        $authList = getDataByArray('Auth', $authId, 'a_id', 'a_id,a_nickname,a_sex,a_type,a_avatar');

        // 加载学校 学段 年级
        $this->school_type = C('SCHOOL_TYPE');

        $school = loadCache('school');
        $schoolType = explode(',', $school[$this->authInfo['s_id']]['s_type']);

        // 查询申请加入群组的成员列表
        $check = M('CrowdCheck')->where(array('cro_id' => $id, 'cc_a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->select();
        $authTmp = getDataByArray('Auth', $check, 'a_id', '*');
        foreach ($check as $k => &$v) {
            if (array_key_exists($v['a_id'], $authTmp)) {
                $v['a_nickname'] = $authTmp[$v['a_id']]['a_nickname'];
                $v['a_sex'] = $authTmp[$v['a_id']]['a_sex'];
                $v['a_type'] = $authTmp[$v['a_id']]['a_type'];
                $v['a_avatar'] = $authTmp[$v['a_id']]['a_avatar'];
            }
        }

        // 赋值
        $this->assign('crowd', $crowd);
        $this->assign('type', intval($type));
        $this->assign('authList', $authList);
        $this->assign('schoolType', $schoolType);
        $this->assign('check', $check);
        $this->display();
    }

    // 删除组中用户
    public function deleteAuth(){

        $authId = intval($_POST['authId']);
        $croId = intval($_POST['croId']);

        $res = M('AuthCrowd')->where(array('a_id' => $authId, 'croId' => $croId))->delete();
        M('Crowd')->where(array('cro_id' => $croId))->setDec('cro_peoples', 1);
        if($res) {
            $result['info'] = '删除成功！';
            $result['status'] = 1;
        }else {
            $result['info'] = '删除失败！';
            $result['status'] = 0;
        }

        echo json_encode($result);
    }

    // 查询用户
    public function searchAuth() {

        // 整理查询条件
        if (intval($_POST['schoolType'])) {
            $map['a_school_type'] = intval($_POST['schoolType']);
        }

        if (intval($_POST['grade'])) {
            $map['a_year'] = GradeToYear(intval($_POST['grade']), $this->authInfo['s_id']);
        }

        if (trim($_POST['authName'])) {
            $map['a_nickname'] = array('LIKE', '%' . trim($_POST['authName']) . '%');
        }

        // 去除群主
        $map['a_id'] = array('neq', $this->authInfo['a_id']);
        $map['s_id'] = $this->authInfo['s_id'];
        $authList = M('Auth')->where($map)->field('a_id,a_nickname,a_sex,a_type,a_avatar')->select();

        echo json_encode($authList);

    }

    // 向群组中添加用户
    public function addCrowdAuth() {

        $croId = intval($_POST['croId']);
        $aidArr = explode(',', $_POST['aidStr']);

        $aidArr = array_filter($aidArr);
        $data['cro_id'] = $croId;
        $data['s_id'] = $this->authInfo['s_id'];

        $model = M('AuthCrowd');

        // 循环添加用户到群组
        foreach ($aidArr as $aid) {
            $data['a_id'] = $aid;
            $res = $model->add($data);
            if($res) {
                $result['status'] = 1;
                $result['info'] = '添加成功！';
            }else {
                $result['status'] = 0;
                $result['info'] = '添加失败！';
            }
        }
        M('Crowd')->where(array('cro_id' => $croId))->setInc('cro_peoples', count($aidArr));
        echo json_encode($result);
    }

    // 更新
    public function update() {

        // 是否有上传
        if ($_FILES['cro_logo']['size'] > 0) {

            // 允许上传图片的类型
            $allowType = C('ALLOW_FILE_TYPE');
            // 上传生成200*200的LOGO
            $_POST['cro_logo'] = parent::upload($allowType['image'], C('CROWD_LOGO_PATH'), TRUE, '96', '96','96/');
        }

        if (!M('Crowd')->where(array('cro_id' => $_POST['cro_id'], 'a_id' => $this->authInfo['a_id']))->getField('cro_id')) {
            $this->error('非法操作');
        }

        parent::update();
    }

    // 获取群组动态
    public function crowdTrend() {

        $cro_id = intval($_POST['cro_id']);
        $is_ajax = intval($_POST['is_ajax']);
        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        if (!$cro_id) {
            echo 0; exit;
        }

        echo D('Trend')->crowdTrend($cro_id, $this->authInfo['s_id'], $is_ajax, $p);

    }

}