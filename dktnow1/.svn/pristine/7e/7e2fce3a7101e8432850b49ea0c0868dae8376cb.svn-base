<?php
/**
 * TacheAction
 * 环节类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-13
 *
 */
class TacheAction extends BaseAction {

    // 初始化
    public function _initialize() {
        parent::_initialize();
    }

    // 环节页
    public function index() {
        $this->redirect('/Index');
    }

    // 添加环节
    public function insert() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 验证课时创建者是否为登录用户
        $where['a_id'] = $this->authInfo['a_id'];
        $where['cl_id'] = intval($_POST['cl_id']);
        $where['co_id'] = intval($_POST['co_id']);
        $Classhour = $this->checkOwner($where, 'Classhour');

        // 添加的节点默认为四个
        // 接收参数
        $_POST['ta_title'] = strval($_POST['ta_title']);
        $_POST['a_id'] = $where['a_id'];
        $_POST['co_id'] = $where['co_id'];
        $_POST['l_id'] = $Classhour['l_id'];
        $_POST['cl_id'] = $where['cl_id'];
        $_POST['ta_created'] = time();

        $result = $this->insertData();
        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));
        $this->success($result);
    }

    // 修改环节
    public function update() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        if (!intval($_POST['ta_id']) || !strval($_POST['ta_title'])) {
            $this->error('非法操作');
        }

        $where['a_id'] = $this->authInfo['a_id'];
        $where['ta_id'] = intval($_POST['ta_id']);
        $data['ta_title'] = strval($_POST['ta_title']);

        // 更新
        $result = M('Tache')->where($where)->save($data);
        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));
        $this->success('操作成功');
    }

    // 删除环节
    public function delete() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 验证课时创建者是否为登录用户
        $where['a_id'] = $this->authInfo['a_id'];
        $where['cl_id'] = intval($_POST['cl_id']);
        $where['co_id'] = intval($_POST['co_id']);
        $Classhour = $this->checkOwner($where, 'Classhour');

        // 查询该节点下是否有活动
        $where['ta_id'] = intval($_POST['ta_id']);
        $hId = M('Tache')->where($where)->getField('act_id');
        if ($hId) {
            $this->error('请先删除该节点下所有的活动');
        }

        // 删除
        $result = M('Tache')->where($where)->delete();
        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));
        $this->success('操作成功');
    }

    // 二维数组变一维
    public function twoToOne($data) {
        $arr = array();
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                $arr[] = $v;
            }
        }
        return $arr;
    }

    // 浏览环节下的题目
    public function browse() {

        if (!intval($_GET['taId'])) {
            $this->redirect('/Index');
        }

        // 判断权限
        if ($this->authInfo['a_type'] == 2) {
            $data['a_id'] = $this->authInfo['a_id'];
        }

        // 验证
        $data['ta_id'] = intval($_GET['taId']);
        $tache = $this->checkOwner($data, 'Tache');

        // 查询活动
        $activity = M('Activity')->where(array('act_id' => array('IN', $tache['act_id'])))->field('act_id,act_is_published,act_type')->select();

        // 查出该环节下的所有活动类型
        $type = array_unique(getValueByField($activity, 'act_type'));

        // 查出该环节下的所有发布的活动ID，针对学生
        $act_id = '';
        foreach ($activity as $key => $value) {
            if ($value['act_is_published'] == 1) {
                $act_id .= $value['act_id'] . ',';
            }
        }
        $act_id = $act_id != '' ? rtrim($act_id, ',') : '';

        // 获取课文id
        $where['l_id'] = $tache['l_id'];

        // 获取课文相关信息
        $lesson = M('Lesson')->where($where)->field('l_pid,l_title')->find();

        // 获取课时名称
        $clName = M('Classhour')->where($where)->getField('cl_title');

        // 获取课文父id
        $where['l_id'] = $lesson['l_pid'];

        // 获取课文相关的单元信息
        $unit = M('Lesson')->where($where)->getField('l_title');

        // 赋值
        $this->lesson = $lesson;
        $this->clName = $clName;
        $this->unit = $unit;
        $this->tache = $tache;
        $this->type = $type;
        $this->act_id = $act_id;

        $this->display($this->template);
    }

    // 查询
    public function search() {

        // 查找环节下活动ID下的作业
        $where['ta_id'] = intval($_POST['ta_id']);
        $where['act_type'] = intval($_POST['act_type']);

        if ($this->authInfo['a_type'] == 2) {
            $activity = M('Activity')->where($where)->field('act_id,act_rel,act_title,act_note')->select();
            $hRel = getValueByField($activity, 'act_rel');
        } else {
            $where['act_id'] = array('IN', strval($_POST['act_id']));
            $activityPublish = setArrayByField(M('ActivityPublish')->where($where)->field('act_id,to_id')->select(), 'act_id');
            $activity = M('Activity')->where($where)->field('act_id,act_rel,act_title,act_note')->select();
            $hRel = getValueByField($activityPublish, 'to_id');
        }

        $id = array('in', explode(',', implode(',', $hRel)));

        // 1 2 为作业和练习
        if ($where['act_type'] == 1 || $where['act_type'] == 2) {

            // 还需查询该活动下是否有附件
            $attachment = getDataByArray('ActivityAttachment', $activity, 'act_id', 'act_id, ar_id');

            $ar_id = getValueByField($attachment, 'ar_id');
            foreach ($ar_id as &$values) {
                $values = trim($values, ',');
            }
            $ar_id = array('in', explode(',', implode(',', $ar_id)));
            $authResource = setArrayByField(M('AuthResource')->where(array('ar_id' => $ar_id))->select(), 'ar_id');
            $img = array();
            foreach ($attachment as $kk => $vv) {
                foreach ($authResource as $kkk => $vvv) {
                    if (strpos($vv['ar_id'], ',' . $vvv['ar_id'] . ',') !== FALSE) {
                        $vvv['img_path'] = getResourceImg($vvv, 0);
                        $img[$kk][] = $vvv;
                    }
                }
            }

            // 如果有关联题目ID
            if ($id) {
                $where['to_id'] = $id;
                $data = setArrayByField(M('Topic')->where($where)->select(), 'to_id');
                $count = 0;
                foreach ($hRel as $k => &$v) {
                    $tmp = ',' . $v . ',';
                    $v = array();
                    foreach ($data as $key => &$value) {
                        $value['to_title'] = stripslashes(stripslashes(htmlspecialchars_decode($value['to_title'])));
                        if (strpos($tmp, ',' . $key . ',') !== FALSE) {
                            $v[$count] = $value;
                            $count++;
                        }
                    }
                    $count = 0;
                }
            } else {
                $hRel = array();
            }

            // 把活动的标题放在数组的最开头
            foreach ($activity as $ak => $av) {
                array_unshift($hRel[$ak], array('act_title' => $av['act_title'], 'attachment' => $img[$av['act_id']]));
            }

        // 3为文本
        } elseif ($where['act_type'] == 3) {
            $hRel = $activity;

        // 4为链接，去链接表里查询相关数据
        }  elseif ($where['act_type'] == 4) {
            $data = setArrayByField(M('Link')->where(array('li_id' => $id))->select(), 'li_id');
            $count = 0;
            foreach ($hRel as $k => &$v) {
                $tmp = ',' . $v . ',';
                $v = array();
                foreach ($data as $key => &$value) {
                    if (strpos($tmp, ',' . $key . ',') !== FALSE) {
                        $v[$count] = $value;
                        $count++;
                    }
                }
                $count = 0;
            }

            // 把活动的标题放在数组的最开头
            foreach ($activity as $ak => $av) {
                array_unshift($hRel[$ak], array('act_title' => $av['act_title']));
            }

         // 5为扩展阅读
        } elseif ($where['act_type'] == 5) {
            $data = setArrayByField(M('AuthResource')->where(array('ar_id' => $id))->select(), 'ar_id');
            $count = 0;
            $img = array();
            foreach ($hRel as $k => &$v) {
                $tmp = ',' . $v . ',';
                $v = array();
                foreach ($data as $key => &$value) {
                    if (strpos($tmp, ',' . $key . ',') !== FALSE) {
                        $value['img_path'] = getResourceImg($value, 0);
                        $img[$k][] = $value;
                    }
                }
            }

            // 把活动的标题放在数组的最开头
            foreach ($activity as $ak => $av) {
                array_unshift($hRel[$ak], array('act_title' => $av['act_title'], 'attachment' => $img[$ak]));
            }
        }


        echo json_encode($hRel);

    }

    // 显示环节列表
    public function lists() {

        // 检测
        if (!intval($_POST['cl_id'])) {
            $this->error('非法操作');
        }

        // 校检该课时是否存在
        $classhour = $this->checkOwner(array('cl_id' => intval($_POST['cl_id'])), 'Classhour', 'cl_id,ar_id');

        // 查询课时下的所有环节
        $tache = M('Tache')->where(array('cl_id' => intval($_POST['cl_id'])))->field('ta_id,ta_sort,act_id,ta_title')->select();

        // 查询课时下所有的活动信息
        foreach ($tache as $tKey => $tValue) {
            if ($tValue['act_id']) {
                $arr[] = explode(',', $tValue['act_id']);
            }
        }

        $arr = $this->twoToOne($arr);
        $activity = setArrayByField(M('Activity')->where(array('act_id' => array('IN', $arr)))->field('act_id,act_title,act_is_published,act_sort,act_type,c_id,cro_id')->select(), 'act_id');

        $activityPublish = setArrayByField(M('ActivityPublish')->where(array('act_id' => array('in', $arr)))->field('ap_id, act_title, act_id, act_type')->select(), 'act_id');

        // 学生
        if ($this->authInfo['a_type'] == 1) {

            // 获取作业答案
            $activityData = M('ActivityData')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('ap_id, ad_id, ad_status')->select();

            $activityData = setArrayByField($activityData, 'ap_id');

            foreach ($activityPublish as $apKey => $apValue) {

                $activityPublish[$apKey]['act_sort'] = $activity[$apValue['act_id']]['act_sort'];

                if ($activityData[$apValue['ap_id']]) {
                    $activityPublish[$apKey]['ad_status'] = $activityData[$apValue['ap_id']]['ad_status'];
                    $activityPublish[$apKey]['ad_id'] = $activityData[$apValue['ap_id']]['ad_id'];
                } else {
                    $activityPublish[$apKey]['ad_status'] = 0;
                    $activityPublish[$apKey]['ad_id'] = 0;
                }
            }

            $activity = $activityPublish;

        }

        // 记录从班群组传过来的c_id和cro_id
        $this->c_id = intval($_POST['c_id']) ? intval($_POST['c_id']) : 0;
        $this->cro_id = intval($_POST['cro_id']) ? intval($_POST['cro_id']) : 0;

         if ($this->authInfo['a_type'] == 2) {

            if ($this->c_id) {

                $activityPublish = setArrayByField(M('ActivityPublish')->where(array('act_id' => array('in', $arr), 'c_id' => $this->c_id))->field('ap_id, act_id')->select(), 'act_id');

            }

            if ($this->cro_id) {
                $activityPublish = setArrayByField(M('ActivityPublish')->where(array('act_id' => array('in', $arr), 'cro_id' => $this->cro_id))->field('ap_id')->select(), 'act_id');
            }

        }

        // 处理URL中是否有带有班级或群组，带的话判断该活动是否已指定该班级，不是的话，加上unclick样式
        foreach ($activity as $k => &$v) {

            if($this->c_id || $this->cro_id) {
                if ($v['c_id'] && $this->c_id && strpos($v['c_id'], ',' . $this->c_id . ',') !== FALSE) {
                    $v['act_flag'] = '';
                } elseif ($v['cro_id'] && $this->cro_id && strpos($v['cro_id'], ',' . $this->cro_id . ',') !== FALSE) {
                    $v['act_flag'] = '';
                } else {
                    $v['act_flag'] = 'unlink';
                }
            } else {
                $v['act_flag'] = '';
            }

        }

        foreach ($tache as $taKey => &$taValue) {
            if ($taValue['act_id']) {
                $arr = explode(',', $taValue['act_id']);
                $tmp = array();
                foreach ($arr as $ks => $vs) {
                    if (array_key_exists($vs, $activity)) {

                        if ($activityPublish[$vs]['ap_id']) {
                            $activity[$vs]['ap_id'] = $activityPublish[$vs]['ap_id'];
                        } else {
                            $activity[$vs]['ap_id'] = 0;
                        }

                        $tmp[] = $activity[$vs];
                    }
                }
                $taValue['act_id'] = $tmp;

                // 活动排序
                foreach ($taValue['act_id'] as $tk => $tv) {
                    $act_sort[$tk] = $tv['act_sort'];
                }

                array_multisort($act_sort, SORT_ASC, $taValue['act_id']);
                unset($act_sort);

            }
        }

        $data['tache'] = $tache;

        echo json_encode($data);
    }

}

?>