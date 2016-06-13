<?php
/**
 * ActivityAction
 * 活动类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-15
 *
 */
class ActivityAction extends BaseAction {

    public function _initialize() {

        parent::_initialize();

        // 活动模版类型
        $this->template = strtolower(ACTION_NAME) . intval($_GET['type']);

    }

    public function index() {

        // 验证接收过来的参数，如果没有参数直接跳到首页去
        if (!isset($_GET['taId'])) {
            $this->redirect('/Index');
        }
    }

    // 接收plupload控件上传的多文件
    public function acceptFiles() {

        // 将扩展名转换为小写
        $pathInfo = explode('.', $_FILES['file']['name']);
        $pathInfo = array_reverse($pathInfo);
        $pathInfo[0] = strtolower($pathInfo[0]);
        $pathInfo = array_reverse($pathInfo);

        $_FILES['file']['name'] = implode('.', $pathInfo);

        $filename = $_FILES['file']['name'];
        $filesize = $_FILES['file']['size'];

        $result = $this->upload($filename, $filesize);

        if ($result) {
           $_SESSION['result'][] = $result;
           echo 1;
        } else {
           echo 0;
        }
    }

    // 上传作业附件
    public function upload($filename, $filesize) {

        if (!$filesize){
           $this->error('不能上传空文件');
        }

        //获取上传文件扩展名
        $result['fileExt'] = pathinfo($filename, PATHINFO_EXTENSION);

        $fileTempName = explode('.', $filename);
        array_pop($fileTempName);

        $result['re_title'] = implode('.', $fileTempName);

        //根据文件扩展名获取文件类型
        $result['fileType'] = getFileTypeByExt($result['fileExt']);

        reloadCache('model');
        $model = loadCache('model');
        $result['m_id'] = $re_type = $model[$result['fileType']]['m_id'];

        if ($result['fileType'] == 'image'){
            $thumb = true;
        } else {
            $thumb = false;
        }

        if (!$re_type){
            $this->error('上传文件类型不支持');
        }

        $result['re_type'] = $re_type;

        $time = date(C('RESOURCE_SAVE_RULES'), time());

        //按照年份目录保存上传文件
        $save = getResourceConfigInfo(0);

        $savePath = $save['Path'][0] . $result['fileType'] . '/';

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        $savePath .= $time . "/";

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        if (in_array($result['m_id'], array(1,3,5))) {

            // 如果是图片放在已转码的文件夹内
            $savePath = $save['Path'][1] . $result['fileType'] . '/';

            if (!is_dir($savePath)) {
                @mkdir($savePath, 0755);
            }

            $savePath .= $time . "/";

            if (!is_dir($savePath)) {
                @mkdir($savePath, 0755);
            }
        }

        $allowType = C('ALLOW_FILE_TYPE');

        $result['savename'] = parent::upload($allowType[$fileType], $savePath, $thumb, '100,600', '75,450', '100/,600/');

        return $result;
    }

    // 进入添加活动页面
    public function add() {

        // 验证接收过来的参数，如果没有参数直接跳到首页去
        if (!isset($_GET['taId']) || !isset($_GET['type'])) {
            $this->redirect('/Index');
        }

        // 验证环节创建者和登陆用户
        $data['a_id'] = $this->authInfo['a_id'];
        $data['ta_id'] = intval($_GET['taId']);
        $result = $this->checkOwner($data, 'Tache');

        // 获取活动的类型
        $this->type = getActivityType($_GET['type']);
        $this->maxSize = intval(ini_get('upload_max_filesize'));

        // 查看是否有在url传班级ID或是群组ID，在传有c_id或是cro_id的情况下，在添加活动页面点击发布
        // 那么只能是发布给班级或群组
        if (intval($_GET['flag']) == 1) {
            $this->unlink = 0;
        } else {
            if (intval($_GET['c_id'])) {
                $this->c_id =intval($_GET['c_id']);
            } elseif (intval($_GET['cro_id'])) {
                $this->cro_id = intval($_GET['cro_id']);
            } else {

                // 查出课时表中已发布的班级和群组
                $data['cl_id'] = $result['cl_id'];
                $classhour = M('Classhour')->where($data)->field('c_id,cro_id')->find();
                if ($classhour) {
                    $this->bindInfo = D('Lesson')->getBindClassAndGroup($this->authInfo['a_id'], $this->authInfo['s_id'], $classhour['c_id'], $classhour['cro_id']);
                    $this->unlink = 1;
                }
            }
        }

        // 作业或练习
        if ($_GET['type'] == 1 || $_GET['type'] == 2) {

            // 取出题库中点击量前45个标签
            $topicTerm = M('TopicTerm')->order('tt_count DESC')->limit(45)->select();
            $this->topicTerm = array_chunk($topicTerm, 15, TRUE);

        }

        // 拓展阅读
        if ($_GET['type'] == 5) {

            // 获取模型列表
            $this->model = loadCache('model');

            // 已添加的资源
            $resource = M('AuthResource')->where(array('ar_id' => array('IN', $this->activity['act_rel'])))->order('ar_id DESC')->select();

            foreach ($resource as $key => $value) {
                $resource[$key]['ar_upload'] = getResourceImg($value);
            }

            // 取出资源标签中点击量前45个标签
            $ResourceTag = M('ResourceTag')->order('rta_count DESC')->limit(45)->select();
            $this->ResourceTag = array_chunk($ResourceTag, 15, TRUE);

            $this->resource = $resource;
        }

        // 把课程id传到页面
        $this->co_id = $result['co_id'];
        $this->ta_id = $result['ta_id'];

        // 限制添加题目的数量
        $this->assign('num', C('ACTIVITY_TOPIC_LIMIT_NUM'));
        $this->display($this->template);
    }

    // 添加活动
    public function insert() {

        // 接收参数，并检测合法性
        $_POST['co_id'] = intval($_POST['co_id']);
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];
        $type = intval($_POST['act_type']);

        $endTime = strval($_POST['ap_complete_time']) ? strval($_POST['ap_complete_time']) : 0;

        // 作业或练习
        if ($type == 1 || $type == 2) {
            $to_id = strval($_POST['act_rel']);

            if (count(explode(',', $to_id)) > C('ACTIVITY_TOPIC_LIMIT_NUM')) {
                $this->error('对不起, 您最多可以添加'.C('ACTIVITY_TOPIC_LIMIT_NUM').'个题目');
            }

        }

        // 链接
        if ($type == 4) {

            if ($_POST['link_name'] && $_POST['link_url']) {

                foreach ($_POST['link_name'] as $key => $value) {
                    $link['li_title'] = $value;
                    $link['li_url'] = $_POST['link_url'][$key];
                    $link['a_id'] = $this->authInfo['a_id'];
                    $link['s_id'] = $this->authInfo['s_id'];
                    $link['li_created'] = time();
                    $linkIdArr[] = M('Link')->add($link);
                }

                $to_id = $_POST['act_rel'] = implode(',', $linkIdArr);
            }
        }

        // 拓展阅读
        if ($type == 5) {
            $to_id = strval($_POST['act_rel']);
        }

        $tache = $this->checkOwner($_POST, 'Tache');

        // 查看环节下是否有act_id，有的话得检测act_id的个数是否等于30
        if ($tache['act_id']) {
            $arr = explode(',', $tache['act_id']);
            if (count($arr) == C('HOMEWORK_MAX_LIMIT')) {
                $this->error('环节下作业的个数不允许超过30份!');
            }
        }

        // 组织参数，写入活动表
        $_POST['l_id'] = $tache['l_id'];
        $_POST['cl_id'] = $tache['cl_id'];
        $_POST['act_is_auto_publish'] = $_POST['act_is_auto_publish'] ? 1 : 0;

        // 如果在URL有c_id或cro_id情况下，用户点击完成，需要过滤传过来的c_id或cro_id字段
        if (intval($_POST['act_is_published']) == 1) {
            $_POST['c_id'] = $_POST['c_id'] == '' ? '' : ',' . $_POST['c_id'] . ',';
            $_POST['cro_id'] = $_POST['cro_id'] == '' ? '' : ',' . $_POST['cro_id'] . ',';
        } elseif (intval($_POST['act_is_published']) == 0) {
            $_POST['c_id'] = '';
            $_POST['cro_id'] = '';
        }
        $result = $this->insertData();

        if ($type == 3) {
            // 生成图片
            generationImg(C('ACTIVITY_TMP_PATH'), $result, $_POST['act_title'], htmlspecialchars_decode($_POST['act_note']));
        }

        // 更新所属环节下的act_id
        $data['act_id'] = $tache['act_id'] ? $tache['act_id'] . ',' . $result : $result;
        M('Tache')->where($_POST)->save($data);
        if (!$result) {
            $this->error('操作失败');
        }

        // 作业或练习
        if ($type == 1 || $type == 2 || $type == 5) {

            if (intval($_POST['uploader_count']) > 0) {

                // 把该活动所属课程标签添加或是更新到资源标签表，并以数组形式返回相关的标签ID
                $course = M('Course')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => $tache['co_id']))->find();
                foreach ($_SESSION['result'] as $va) {
                    $rta_id = turnIdToWord($course, 'ResourceTag');
                }
                $rta_id = ',' . implode(',', $rta_id) . ',';

                // 读取配置文件，看看文档和视频是否需要自动转码
                $documentCode = C('AUTO_TRANS_DOCUMENT');
                $videoCode = C('AUTO_TRANS_VIDEO');

                // 根据上传的资源数量，依次写入我的资源表
                if ($_SESSION['result']) {

                    if ($type != 5) {

                        // 用户上传一个资源加一分
                        M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

                        $data['a_id'] = $this->authInfo['a_id'];
                        $data['s_id'] = $this->authInfo['s_id'];

                        // 添加多个资源
                        foreach ($_SESSION['result'] as $key => $value) {

                            $data['m_id'] = $value['m_id'];
                            $data['ar_title'] = $value['re_title'];
                            $data['ar_ext'] = $value['fileExt'];
                            $data['ar_savename'] = $value['savename'];
                            $data['rta_id'] = $rta_id;
                            $data['ar_created'] = time();

                            if (in_array($value['m_id'], array(1,3,5))) {
                                $data['ar_is_transform'] = 1;
                            }

                            $table = getResourceConfigInfo(0);
                            $idss = M($table['TableName'])->add($data);
                            $ar_id[] = $idss;

                            // 文档
                            if ($data['m_id'] == 4) {
                                $doc[] = $idss;
                            }

                            // 视频
                            if ($data['m_id'] == 2) {
                                $video[] = $idss;
                            }

                        }

                        // 如果为1且文上传附件中有文档，就立即转码
                        if ($documentCode && $doc) {
                            trans(0, $doc);
                        }

                        // 如果为1且文上传附件中有视频，就立即转码
                        if ($videoCode && $video) {
                            trans(0, $video);
                        }

                        $ar_id = ',' . implode(',', $ar_id) . ',';
                    } else {
                        $ar_id = ',' . $to_id . ',';
                    }

                    // 销毁该session
                    unset($_SESSION['result']);

                    // 把我的资源表的资源ID连起来写入活动附件表
                    $activityAttachment['s_id'] = $this->authInfo['s_id'];
                    $activityAttachment['a_id'] = $this->authInfo['a_id'];
                    $activityAttachment['co_id'] = $tache['co_id'];
                    $activityAttachment['l_id'] = $tache['l_id'];
                    $activityAttachment['cl_id'] = $tache['cl_id'];
                    $activityAttachment['ta_id'] = $tache['ta_id'];
                    $activityAttachment['act_id'] = $result;
                    $activityAttachment['ar_id'] = $ar_id;
                    $activityAttachment['act_type'] = intval($_POST['act_type']);
                    $activityAttachment['aat_created'] = time();
                    M('ActivityAttachment')->add($activityAttachment);
                }
            }

        }

        // 如果是点击发布的话
        if (intval($_POST['act_is_published']) == 1) {

            // 动态表
            if (intval($_POST['act_type']) == 1) {
                $action = 2;
                $obj = 1;
            } elseif (intval($_POST['act_type']) == 2) {
                $action = 2;
                $obj = 2;
            }

            $_POST['s_id'] = $this->authInfo['s_id'];
            $_POST['a_id'] = $this->authInfo['a_id'];
            $_POST['co_id'] = $tache['co_id'];
            $_POST['l_id'] = $tache['l_id'];
            $_POST['cl_id'] = $tache['cl_id'];
            $_POST['ta_id'] = $tache['ta_id'];
            $_POST['to_id'] = $to_id ? $to_id : '';
            $_POST['act_id'] = $result;
            $_POST['ap_complete_time'] = $type == 1 ? strtotime($endTime) : $endTime;
            $_POST['ap_created'] = time();
            $_POST['ap_course'] = M('Course')->where(array('a_id' => $_POST['a_id'], 'co_id' => $_POST['co_id']))->getField('co_subject');

            // 如果有绑定班级或是群组的话，刚刚发布的活动记录给添加到活动发布表里去
            if (strval($_POST['c_id']) != '' || strval($_POST['cro_id']) != '') {

                $cro_id = explode(',', strval(trim($_POST['cro_id'], ',')));
                $c_id = explode(',', strval(trim($_POST['c_id'], ',')));
                unset($_POST['c_id']);
                unset($_POST['cro_id']);

                // 更新课时发布表相关的c_id和cro_id
                $where['cl_id'] = intval($_POST['cl_id']);
                $where['a_id'] = $this->authInfo['a_id'];
                $where['s_id'] = $this->authInfo['s_id'];
                $res = M('ClasshourPublish')->where($where)->field('cp_id,act_id,cl_id')->select();

                // 活动发布对象总人数
                $peoples['act_peoples'] = 0;

                if ($c_id) {

                    // 获取班级人数
                    $classInfo = M('Class')->where(array('c_id' => array('IN', $c_id), 's_id' => $this->authInfo['s_id']))->field('c_id, c_peoples')->select();
                    $classInfo = setArrayByField($classInfo, 'c_id');

                    foreach ($c_id as $key => $value) {
                        $_POST['c_id'] = $value;
                        $_POST['ap_peoples'] = $classInfo[$value]['c_peoples'];
                        $peoples['act_peoples'] += $classInfo[$value]['c_peoples'];
                        $where['c_id'] = $value;
                        M('ActivityPublish')->add($_POST);

                        foreach ($res as $k => $v) {
                            $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $result : $result;
                            $save['cp_updated'] = time();
                            $where['cp_id'] = $v['cp_id'];
                            M('ClasshourPublish')->where($where)->save($save);

                         }

                         addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $value, $action, $obj, 0, M('Course')->where(array('co_id' => $tache['co_id']))->getField('co_subject'), strval($_POST['act_title']), $result);
                    }

                    unset($_POST['c_id']);
                    unset($where['c_id']);
                }

                if ($cro_id) {

                    // 获取群组信息
                    $crowdAuth = M('AuthCrowd')->where(array('cro_id' => array('IN', $cro_id)))->select();

                    $crowdInfo = array();
                    foreach ($crowdAuth as $key => $value) {
                        if (in_array($value['cro_id'], $cro_id)) {
                            $crowdInfo[$value['cro_id']]['num'] += 1;
                        }
                    }

                    foreach ($cro_id as $key => $value) {
                        $_POST['cro_id'] = $value;
                        $_POST['ap_peoples'] = $crowdInfo[$value]['num'];
                        $peoples['act_peoples'] += $crowdInfo[$value]['num'];
                        $where['cro_id'] = $value;
                        M('ActivityPublish')->add($_POST);
                        foreach ($res as $k => $v) {
                            $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $result : $result;
                            $save['cp_updated'] = time();
                            $where['cp_id'] = $v['cp_id'];
                            M('ClasshourPublish')->where($where)->save($save);
                         }
                    }
                    unset($_POST['cro_id']);
                }
            }

            // 更新发布对象的人数
            M('Activity')->where(array('act_id' => $result))->save($peoples);

            // 更新题目发布对象的总人数
            if (strval($_POST['act_rel'])) {

                $to_peoples['to_peoples'] = $peoples['act_peoples'];

                M('Topic')->where(array('to_id' => array('IN', strval($_POST['act_rel']))))->save($to_peoples);
            }

        }

        $cCode = '';
        $gCode = '';
        // 查看是否传有班级和群组状态ID
        if (intval($_POST['c_idCode']) || intval($_POST['cro_idCode'])) {
            $cCode = intval($_POST['c_idCode']) ? '/c_id/' . intval($_POST['c_idCode']) : '';
            $gCode = intval($_POST['cro_idCode']) ? '/cro_id/' . intval($_POST['cro_idCode']) : '';
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));

        // 重新转向作业所属的环节
        //$this->redirect('/Tache/index/cl/' . $tache['cl_id'] . ($cCode ? $cCode : $gCode));

        $this->success($result);
    }

    // 修改活动
    public function edit() {

        // 如果没有传相关参数，便转到首页去
        if (!isset($_GET['act_id'])) {
            $this->redirect('/Index');
        }
        $this->maxSize = intval(ini_get('upload_max_filesize'));
        // 验证环节创建者和登陆用户
        $data['a_id'] = $this->authInfo['a_id'];
        $data['act_id'] = intval($_GET['act_id']);
        $activity = $this->checkOwner($data, 'Activity');

        // 查出课时表中已发布的班级和群组，同时还得加另一个判断，如果这个编辑的活动一开始并
        // 没有发布，那么还需查询所属课时下已发布的班级和群组，这时得加个状态值来区分
        // 查看是否有在url传班级ID或是群组ID，有的话就是在备课的情况下添加作业的
        // 在编辑时，首先得查看url是否带有c_id或是cro_id是否在已发布的字段里，是的话便不弹窗
        if (intval($_GET['flag']) == 1) {
            $this->unlink = 0;
        } else {
            if (intval($_GET['c_id'])) {
                if ($activity['c_id'] && strpos($activity['c_id'], ',' . $_GET['c_id'] . ',') !== FALSE) {
                    $this->isShow = 1;
                } else {
                    $this->isShow = 0;
                }
                $this->c_idCode =intval($_GET['c_id']);
            } elseif (intval($_GET['cro_id'])) {
                if ($activity['cro_id'] && strpos($activity['cro_id'], ',' . $_GET['cro_id'] . ',') !== FALSE) {
                    $this->isShow = 1;
                } else {
                    $this->isShow = 0;
                }
                $this->cro_idCode = intval($_GET['cro_id']);
            } else {

                // 查出课时表中已发布的班级和群组
                $data['cl_id'] = $activity['cl_id'];
                $classhour = M('Classhour')->where($data)->field('c_id,cro_id')->find();
                if ($classhour) {
                    $this->bindInfo = D('Lesson')->getBindClassAndGroup($this->authInfo['a_id'], $this->authInfo['s_id'], $classhour['c_id'], $classhour['cro_id']);
                    $this->unlink = 1;
                }
            }
        }

        // 如果该活动下的c_id或是cro_id有一个不为空，则说明该活动已经发布过
        // editCode为0说明该活动没发布过，1为发布过
        if ($activity['c_id'] != '' || $activity['cro_id'] != '') {
            $this->editCode = 1;
        } else {
            $this->editCode = 0;
        }

        // 作业或练习
        if ($activity['act_type'] == 1 || $activity['act_type'] == 2) {

            // 在编辑时，需要去活动附件表查询该活动是否有附件，有的话便到我的资源里去查询
            $ar_id = M('ActivityAttachment')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id'], 'act_id' => $activity['act_id']))->getField('ar_id');
            if ($ar_id) {
                $lists = listAttachments($ar_id);
                $this->uploadFiles = $lists;
            }

            // 限制添加题目的数量
            $this->assign('num', C('ACTIVITY_TOPIC_LIMIT_NUM'));

            // 取出题库中点击量前45个标签
            $topicTerm = M('TopicTerm')->order('tt_count DESC')->limit(45)->select();
            $this->topicTerm = array_chunk($topicTerm, 15, TRUE);
        }

        // 链接
        if ($activity['act_type'] == 4) {
            $links = M('Link')->where(array('li_id' => array('IN', $activity['act_rel'])))->select();
            $this->links = $links;
        }

        // 拓展阅读
        if ($activity['act_type'] == 5) {

            // 获取模型列表
            $this->model = loadCache('model');

            // 已添加的资源
            $resource = M('AuthResource')->where(array('ar_id' => array('IN', $activity['act_rel'])))->order('ar_id DESC')->select();

            foreach ($resource as $key => $value) {
                $resource[$key]['ar_upload'] = getResourceImg($value);
            }

            // 取出资源标签中点击量前45个标签
            $ResourceTag = M('ResourceTag')->order('rta_count DESC')->limit(45)->select();
            $this->ResourceTag = array_chunk($ResourceTag, 15, TRUE);

            $this->resource = $resource;
        }

        // 显示编辑的活动的名称
        $this->type = getActivityType($activity['act_type']);

        $this->activity = $activity;
        $this->display(strtolower(ACTION_NAME . $activity['act_type']));
    }

    // 更新活动
    public function update() {

        // 接收参数，并检测合法性
        $where['act_id'] = intval($_POST['act_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $activity = $this->checkOwner($where, 'Activity');

        // 作业或练习
        if ($activity['act_type'] == 1 || $activity['act_type'] == 2) {

            $to_id = strval($_POST['act_rel']);

            if (count(explode(',', $to_id)) > C('ACTIVITY_TOPIC_LIMIT_NUM')) {
                $this->error('对不起, 您最多可以添加'.C('ACTIVITY_TOPIC_LIMIT_NUM').'个题目');
            }
        }

        if ($activity['act_type'] == 3) {

            // 生成图片
            generationImg(C('ACTIVITY_TMP_PATH'), intval($_POST['act_id']), $_POST['act_title'], htmlspecialchars_decode($_POST['act_note']));
        }

        // 链接
        if ($activity['act_type'] == 4) {

            foreach ($_POST['link_name'] as $key => $value) {

                $link['li_title'] = $value;
                $link['li_url'] = $_POST['link_url'][$key];
                $link['a_id'] = $this->authInfo['a_id'];
                $link['s_id'] = $this->authInfo['s_id'];

                if (intval($_POST['li_id'][$key])) {
                    $link['li_updated'] = time();
                    M('Link')->where(array('li_id' => intval($_POST['li_id'][$key])))->save($link);
                } else {
                    $link['li_created'] = time();
                    $link['li_updated'] = 0;
                    $linkIdArr[] = M('Link')->add($link);
                }
            }

            if ($linkIdArr && $_POST['li_id']) {
                $ids = implode(',', array_merge($_POST['li_id'], $linkIdArr));
            }

            if ($linkIdArr && !$_POST['li_id']) {
                $ids = implode(',', $linkIdArr);
            }

            if (!$linkIdArr && $_POST['li_id']) {
                $ids = implode(',', $_POST['li_id']);
            }

            $to_id = $_POST['to_id'] = $_POST['act_rel'] = $ids;
        }

        // 如果是拓展阅读
        if ($activity['act_type'] == 5) {
            $to_id = $_POST['act_rel'];
        }

        // 活动发布对象总人数
        $peoples['act_peoples'] = $activity['act_peoples'];

        // 组织参数
        $_POST['act_is_auto_publish'] = $_POST['act_is_auto_publish'] ? 1 : 0;

        // 如果编辑页面上传过来publishFlag则只更新是否自动发布字段，同时还可以更新未发布的班级和群组
        if ($_POST['publishFlag'] == 1) {

            $save['act_is_auto_publish'] = $_POST['act_is_auto_publish'];
            $save['act_updated'] = time();

           if (strval($_POST['c_id'])) {
               $save['c_id'] = $activity['c_id'] ? $activity['c_id'] . strval($_POST['c_id']) . ',' : ',' . strval($_POST['c_id']) . ',';
           }

           if (strval($_POST['cro_id'])) {
               $save['cro_id'] = $activity['cro_id'] ? $activity['cro_id'] . strval($_POST['cro_id'])  . ',' : ',' . strval($_POST['cro_id']) . ',';
           }
           M('Activity')->where($where)->save($save);

        } else {

            if (strval($_POST['c_id'])) {
               $_POST['c_id'] = $activity['c_id'] ? $activity['c_id'] . strval($_POST['c_id']) . ',': (strval($_POST['c_id']) ? ',' . strval($_POST['c_id']) . ',' : '');
           }

           if (strval($_POST['cro_id'])) {
               $_POST['cro_id'] = $activity['cro_id'] ? $activity['cro_id'] . strval($_POST['cro_id']) . ',' : (strval($_POST['cro_id']) ? ',' . strval($_POST['cro_id']) . ',': '');
           }

            // 更新活动表
            $this->updateData();

        }

        // 作业或练习
        if ($activity['act_type'] == 1 || $activity['act_type'] == 2 || $activity['act_type'] == 5) {

            if (intval($_POST['uploader_count']) > 0) {

                // 把该活动所属课程标签添加或是更新到资源标签表，并以数组形式返回相关的标签ID
                $course = M('Course')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => $activity['co_id']))->find();
                foreach ($_SESSION['result'] as $va) {
                    $rta_id = turnIdToWord($course, 'ResourceTag');
                }
                $rta_id = ',' . implode(',', $rta_id) . ',';

                // 读取配置文件，看看文档和视频是否需要自动转码
                $documentCode = C('AUTO_TRANS_DOCUMENT');
                $videoCode = C('AUTO_TRANS_VIDEO');

                // 根据上传的资源数量，依次写入我的资源表
                if ($_SESSION['result']) {

                    if ($activity['act_type'] != 5) {

                        // 用户上传一个资源加一分
                        M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

                        $data['a_id'] = $this->authInfo['a_id'];
                        $data['s_id'] = $this->authInfo['s_id'];

                        // 添加多个资源
                        foreach ($_SESSION['result'] as $key => $value) {

                            $data['m_id'] = $value['m_id'];
                            $data['ar_title'] = $value['re_title'];
                            $data['ar_ext'] = $value['fileExt'];
                            $data['ar_savename'] = $value['savename'];
                            $data['rta_id'] = $rta_id;
                            $data['ar_created'] = time();

                            if (in_array($value['m_id'], array(1,3,5))) {
                                $data['ar_is_transform'] = 1;
                            }

                            $table = getResourceConfigInfo(0);
                            $idss = M($table['TableName'])->add($data);
                            $ar_id[] = $idss;

                            // 文档
                            if ($data['m_id'] == 4) {
                                $doc[] = $idss;
                            }

                            // 视频
                            if ($data['m_id'] == 2) {
                                $video[] = $idss;
                            }

                        }

                        // 如果为1且文上传附件中有文档，就立即转码
                        if ($documentCode && $doc) {
                            trans(0, $doc);
                        }

                        // 如果为1且文上传附件中有视频，就立即转码
                        if ($videoCode && $video) {
                            trans(0, $video);
                        }

                        $ar_id = ',' . implode(',', $ar_id) . ',';
                    } else {
                        $ar_id = ',' . $to_id . ',';
                    }



                    // 销毁该session
                    unset($_SESSION['result']);

                    // 依据活动ID查询活动附件表，有数据记录的话就是更新，否则就是写入
                    $activityAttachment['s_id'] = $this->authInfo['s_id'];
                    $activityAttachment['a_id'] = $this->authInfo['a_id'];
                    $activityAttachment['act_id'] = $activity['act_id'];
                    $infos = M('ActivityAttachment')->where($activityAttachment)->find();
                    if ($infos) {
                        $saveDatas['ar_id'] = $infos['ar_id'] == '' ? $ar_id : $infos['ar_id'] . ltrim($ar_id, ',');
                        $saveDatas['aat_updated'] = time();
                        M('ActivityAttachment')->where(array('aat_id' => $infos['aat_id']))->save($saveDatas);
                    } else {
                        $activityAttachment['co_id'] = $activity['co_id'];
                        $activityAttachment['l_id'] = $activity['l_id'];
                        $activityAttachment['cl_id'] = $activity['cl_id'];
                        $activityAttachment['ta_id'] = $activity['ta_id'];
                        $activityAttachment['ar_id'] = $ar_id;
                        $activityAttachment['act_type'] = $activity['act_type'];
                        $activityAttachment['aat_created'] = time();
                        M('ActivityAttachment')->add($activityAttachment);
                    }
                }
            }
        }

        // 如果是点击发布的话
        if (intval($_POST['act_is_published']) == 1) {

            // 动态表
            if ($activity['act_type'] == 1) {
                $action = 2;
                $obj = 1;
            } elseif ($activity['act_type'] == 2) {
                $action = 2;
                $obj = 2;
            }

            $_POST['s_id'] = $this->authInfo['s_id'];
            $_POST['a_id'] = $this->authInfo['a_id'];
            $_POST['co_id'] = $activity['co_id'];
            $_POST['l_id'] = $activity['l_id'];
            $_POST['cl_id'] = $activity['cl_id'];
            $_POST['ta_id'] = $activity['ta_id'];
            $_POST['to_id'] = $to_id ? $to_id : '';
            $_POST['act_type'] = $activity['act_type'];
            $_POST['ap_complete_time'] = $activity['act_type'] == 2 ? intval($_POST['ap_complete_time']) : strtotime($_POST['ap_complete_time']);
            $_POST['ap_created'] = time();
            $_POST['ap_course'] = M('Course')->where(array('a_id' => $_POST['a_id'], 'co_id' => $_POST['co_id']))->getField('co_subject');

            // 如果有绑定班级或是群组的话，刚刚发布的活动记录给添加到活动发布表里去
            if (strval($_POST['c_id']) != '' || strval($_POST['cro_id']) != '') {

                $c_id = explode(',', strval($_POST['c_id']));
                $cro_id = explode(',', strval($_POST['cro_id']));
                unset($_POST['c_id']);
                unset($_POST['cro_id']);

                $where1['cl_id'] = intval($_POST['cl_id']);
                $where1['a_id'] = $this->authInfo['a_id'];
                $where1['s_id'] = $this->authInfo['s_id'];
                $res = M('ClasshourPublish')->where($where1)->field('cp_id,act_id,cl_id')->select();

                if ($c_id) {

                    // 获取班级人数
                    $classInfo = M('Class')->where(array('c_id' => array('IN', $c_id), 's_id' => $this->authInfo['s_id']))->field('c_id, c_peoples')->select();
                    $classInfo = setArrayByField($classInfo, 'c_id');

                    foreach ($c_id as $key => $value) {
                        $_POST['c_id'] = $value;
                        $where1['c_id'] = $value;
                        $_POST['ap_peoples'] = $classInfo[$value]['c_peoples'];
                        $peoples['act_peoples'] += $classInfo[$value]['c_peoples'];

                        M('ActivityPublish')->add($_POST);

                        foreach ($res as $k => $v) {
                            if ($v['act_id'] != '') {
                                if (strpos(',' . $v['act_id'] . ',', ',' . intval($_POST['act_id']) .',') !== FALSE) {
                                    continue;
                                } else {
                                    $save['act_id'] = $v['act_id'] . ',' . intval($_POST['act_id']);
                                }
                            } elseif ($v['act_id'] == ''){
                                $save['act_id'] = intval($_POST['act_id']);
                            }
                            $save['cp_updated'] = time();
                            $where1['cp_id'] = $v['cp_id'];
                            M('ClasshourPublish')->where($where1)->save($save);
                         }

                         addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $value, $action, $obj, 0, M('Course')->where(array('co_id' => $activity['co_id']))->getField('co_subject'), strval($_POST['act_title']), intval($_POST['act_id']));
                    }
                }
                unset($_POST['c_id']);
                unset($where1['c_id']);

                if ($cro_id) {

                    // 获取群组信息
                    $crowdAuth = M('AuthCrowd')->where(array('cro_id' => array('IN', $cro_id)))->select();

                    $crowdInfo = array();
                    foreach ($crowdAuth as $key => $value) {
                        if (in_array($value['cro_id'], $cro_id)) {
                            $crowdInfo[$value['cro_id']]['num'] += 1;
                        }
                    }

                    foreach ($cro_id as $key => $value) {
                        $_POST['cro_id'] = $value;
                        $where1['cro_id'] = $value;
                        $_POST['ap_peoples'] = $crowdInfo[$value]['num'];
                        $peoples['act_peoples'] += $crowdInfo[$value]['num'];
                        M('ActivityPublish')->add($_POST);
                        foreach ($res as $k => $v) {
                            if ($v['act_id'] != '') {
                                if (strpos(',' . $v['act_id'] . ',', ',' . intval($_POST['act_id']) .',') !== FALSE) {
                                    continue;
                                } else {
                                    $save['act_id'] = $v['act_id'] . ',' . intval($_POST['act_id']);
                                }
                            } elseif ($v['act_id'] == ''){
                                $save['act_id'] = intval($_POST['act_id']);
                            }
                            $save['cp_updated'] = time();
                            $where1['cp_id'] = $v['cp_id'];
                            M('ClasshourPublish')->where($where1)->save($save);
                         }
                    }
                }
            }

            // 更新发布对象的人数
            M('Activity')->where(array('act_id' => $where['act_id']))->save($peoples);

            // 更新题目发布对象的总人数
            if (strval($_POST['act_rel'])) {

                $to_peoples['to_peoples'] = $peoples['act_peoples'];

                M('Topic')->where(array('to_id' => array('IN', strval($_POST['act_rel']))))->save($to_peoples);
            }
        }



        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));
        $cCode = '';
        $gCode = '';
        // 查看是否传有班级和群组状态ID
        if (intval($_POST['c_idCode']) || intval($_POST['cro_idCode'])) {
            $cCode = intval($_POST['c_idCode']) ? '/c_id/' . intval($_POST['c_idCode']) : '';
            $gCode = intval($_POST['cro_idCode']) ? '/cro_id/' . intval($_POST['cro_idCode']) : '';
        }

        // 重新转向所属的环节
        //$this->redirect('/Tache/index/cl/' . $activity['cl_id'] . ($cCode ? $cCode : $gCode));

        $this->success('更新成功');
    }

    // 删除活动
    public function delete() {

        // 权限检测
        $data['a_id'] = $this->authInfo['a_id'];
        $data['ta_id'] = intval($_POST['ta_id']);
        $this->tache = $this->checkOwner($data, 'Tache');

        // 查询该活动是否已发布，没有则可以删除，否则就不能
        $data['act_id'] = intval($_POST['act_id']);
        $flag = M('Activity')->where($data)->getField('act_is_published');
        if ($flag == 1) {
            $this->error('该活动已经被发布，不能删除');
        }

        // 删除
        $result = M('Activity')->where($data)->delete();
        if (!$result) {
            $this->error('删除失败');
        }

        // 若是该活动下有附件的话，还需把附件给一并删除
        deleteDirectory(C('ACTIVITY_PATH') . ($data['act_id'] % C('MAX_FILES_NUM')) . '/' . $data['act_id'] . '/');

        // 删除生成的活动文本图片
        unlink(C('ACTIVITY_TMP_PATH').'image/'.intval($_POST['act_id']).'.png');
        unlink(C('ACTIVITY_TMP_PATH').'html/'.intval($_POST['act_id']).'.html');

        // 删除活动附件表中的数据，并把相关的资源标签使用次数减一
        $data['s_id'] = $this->authInfo['s_id'];
        $activityAttachment = M('ActivityAttachment')->where($data)->find();
        if ($activityAttachment) {

            // 删除该条数据
            M('ActivityAttachment')->where($data)->delete();

            // 把资源相关的标签使用次数-1
            $rta_id = M('AuthResource')->where(array('ar_id' => array('IN', trim($activityAttachment['ar_id'], ','))))->field('rta_id')->select();
            foreach ($rta_id as $rkey => $rvalue) {
                M('ResourceTag')->where(array('rta_id' => array('IN', trim($rvalue['rta_id'], ','))))->save(array('rta_count' => array('exp', 'rta_count-1')));
            }
        }

        // 最后把关联环节的活动ID给清掉
        $save['act_id'] = $this->recombineString($this->tache['act_id'], $data['act_id']);
        $save['act_updated'] = time();
        $where['ta_id'] = $data['ta_id'];
        M('Tache')->where($where)->save($save);

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $this->tache['co_id']))->save(array('co_updated' => time()));

        $this->success('删除成功');
    }

    // 清掉属于字符串中的值，如1,2,3中2，清掉后重组字符串便是1,3,flag为0是重组环节，1是重组资源标签
    public function recombineString($strings, $string, $flag = 0) {

        // 如果只有一个值，直接返回|:
        if (strlen($strings) == strlen($string) && $strings == ($flag == 0 ? $string : ',' . $string . ',')) {
            return '';
        }

        // 把字符串拆成数组，找到相等的便从数组中拿到，退出，重组字符串返回
        if ($flag) {
            $strings = explode(',', trim($strings, ','));
        } else {
            $strings = explode(',', $strings);
        }
        foreach ($strings as $key => &$value) {
            if ($value == $string) {
                unset($strings[array_search($string, $strings)]);
                break;
            }
        }

        $strings = implode(',', $strings);
        if ($flag) {
            $strings = ',' . $strings . ',';
        }
        return $strings;
    }

    // 异步删除活动编辑页中的附件
    public function syncMoveFile() {

        // 验证
        $ar_id = intval($_POST['ar_id']);
        $where['act_id'] = intval($_POST['act_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $where['s_id'] = $this->authInfo['s_id'];
        if (!$ar_id || !$where['act_id']) {
            $this->error('非法操作');
        }

        $rta_id = M('AuthResource')->where(array('ar_id' => $ar_id))->getField('rta_id');
        if (!$rta_id) {
            $this->error('非法操作');
        }

        // 把资源相关的标签使用次数-1
        M('ResourceTag')->where(array('rta_id' => array('IN', trim($rta_id, ','))))->save(array('rta_count' => array('exp', 'rta_count-1')));

        // 去活动附件表解除该资源ID
        $activityAttachment = M('ActivityAttachment')->where($where)->find();
        if (!$activityAttachment) {
            $this->error('非法操作');
        }
        $save['ar_id'] = $this->recombineString($activityAttachment['ar_id'], $ar_id, 1);

        // 保存
        $result = M('ActivityAttachment')->where($where)->save($save);
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');

    }

    // 清除没有发布的活动的关联关系
    public function deleteRelation() {

        // 权限检测
        $data['a_id'] = $this->authInfo['a_id'];
        $data['act_id'] = intval($_POST['act_id']);
        $activity = $this->checkOwner($data, 'Activity');

        // 作业和练习时题目ID
        if ($activity['act_type'] == 1 || $activity['act_type'] == 2) {
            $id = strval($_POST['to_id']);
        }

        // 删除外链
        if ($activity['act_type'] == 4) {
            $id = strval($_POST['li_id']);
            M('Link')->where(array('li_id' => $id))->delete();
        }

        // 拓展阅读 用户资源id
        if ($activity['act_type'] == 5) {
            $id = strval($_POST['ar_id']);
        }

        // 解除传过来的题目id
        $save['act_rel'] = $this->recombineString($activity['act_rel'], $id);
        $result = M('Activity')->where($data)->save($save);
        if (!$result) {
            $this->error('删除失败');
        }

        $this->success('删除成功');

    }


    // 下载
    public function download() {

        $id = intval($_REQUEST['id']);

        D('Activity')->download($id);
    }

    // 拓展阅读 多文件上传
    public function uploadAttach() {

        // 将扩展名转换为小写
        $pathInfo = explode('.', $_FILES['file']['name']);
        $pathInfo = array_reverse($pathInfo);
        $pathInfo[0] = strtolower($pathInfo[0]);
        $pathInfo = array_reverse($pathInfo);

        $_FILES['file']['name'] = implode('.', $pathInfo);

        $filename = $_FILES['file']['name'];
        $filesize = $_FILES['file']['size'];

        $result = $this->uploadResource($filename, $filesize);

        if ($result) {
           $_SESSION['result'][] = $result;
           echo 1;
        } else {
           echo 0;
        }
    }

    // 拓展阅读 资源上传
    public function uploadResource($filename, $filesize) {

        if (!$filesize){
            $this->error('不能上传空文件');
        }

        //获取上传文件扩展名
        $pathInfo = pathInfo($filename);
        $result['filename'] = $pathInfo['filename'];
        $result['fileExt'] = $pathInfo['extension'];

        $fileTempName = explode('.', $filename);
        array_pop($fileTempName);

        $result['re_title'] = implode('.', $fileTempName);

        //根据文件扩展名获取文件类型
        $result['fileType'] = getFileTypeByExt($result['fileExt']);

        $model = loadCache('model');

        $result['m_id'] = $re_type = $model[$result['fileType']]['m_id'];

        if ($result['fileType'] == 'image'){
            $thumb = true;
        } else {
            $thumb = false;
        }

        if (!$re_type){
            $this->error('上传文件类型不支持');
        }

        $result['re_type'] = $re_type;

        $time = date(C('RESOURCE_SAVE_RULES'), time());

        //按照年份目录保存上传文件
        $save = getResourceConfigInfo(0);

        $savePath = $save['Path'][0] . $result['fileType'] . '/';

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        $savePath .= $time . "/";

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        if (in_array($result['m_id'], array(1,3,5))) {

            // 如果是图片放在已转码的文件夹内
            $savePath = $save['Path'][1] . $result['fileType'] . '/';

            if (!is_dir($savePath)) {
                @mkdir($savePath, 0755);
            }

            $savePath .= $time . "/";

            if (!is_dir($savePath)) {
                @mkdir($savePath, 0755);
            }
        }

        $allowType = C('ALLOW_FILE_TYPE');

        $result['savename'] = parent::upload($allowType[$fileType], $savePath, $thumb, '100,600', '75,450', '100/,600/');

        return $result;
    }

    // 拓展阅读 添加用户资源
    public function insertAuthResource() {

        $data['a_id'] = $this->authInfo['a_id'];
        $data['ar_created'] = time();
        $data['s_id'] = $this->authInfo['s_id'];

        // 把该活动所属课程标签添加或是更新到资源标签表，并以数组形式返回相关的标签ID
        $course = M('Course')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => intval($_POST['co_id'])))->find();
        foreach ($_SESSION['result'] as $va) {
            $rta_id = turnIdToWord($course, 'ResourceTag');
        }
        $rta_id = ',' . implode(',', $rta_id) . ',';

        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 用户上传一个资源加一分
        M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

        if ($_SESSION['result']) {

            // 读取配置文件，看看文档和视频是否需要自动转码
            $documentCode = C('AUTO_TRANS_DOCUMENT');
            $videoCode = C('AUTO_TRANS_VIDEO');

            // 添加多个资源
            foreach ($_SESSION['result'] as $key => $value) {

                $data['m_id'] = $value['m_id'];
                $data['ar_title'] = $value['re_title'];
                $data['ar_ext'] = $value['fileExt'];
                $data['ar_savename'] = $value['savename'];
                $data['rta_id'] = $rta_id;

                if (in_array($value['m_id'], array(1,3,5))) {
                    $data['ar_is_transform'] = 1;
                    $result[$key]['ar_is_transform'] = 1;
                } else {
                    $data['ar_is_transform'] = 0;
                    $result[$key]['ar_is_transform'] = 0;
                }

                $table = getResourceConfigInfo(0);

                $idss = M($table['TableName'])->add($data);
                $result[$key]['ar_id'] = $idss;
                $result[$key]['ar_title'] = $value['re_title'];
                $result[$key]['ar_upload'] = getResourceImg($data);

                // 文档
                if ($data['m_id'] == 4) {
                    $doc[] = $idss;
                }

                // 视频
                if ($data['m_id'] == 2) {
                    $video[] = $idss;
                }

            }

            // 如果为1且文上传附件中有文档，就立即转码
            if ($documentCode && $doc) {
                trans(0, $doc);
            }

            // 如果为1且文上传附件中有视频，就立即转码
            if ($videoCode && $video) {
                trans(0, $video);
            }

            echo json_encode($result);

        } else {
            echo 0;
        }
    }

    // 查询
    public function search() {

        // 查找环节下活动ID下的作业
        $where['act_id'] = intval($_POST['act_id']);
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

            foreach ($hRel as $hk => $hv) {
                $hRel[$hk]['act_note'] = htmlspecialchars_decode($hv['act_note']);
            }

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
        } elseif ($where['act_type'] == 6) {
            $hRel = $activity;
            foreach ($hRel as $hk => $hv) {
                $hRel[$hk]['act_note'] = htmlspecialchars_decode($hv['act_note']);
            }
        }

        echo json_encode($hRel);

    }

    // 活动排序
    public function updateSort() {

        // 检测
        if (!intval($_POST['ta_id']) || !strval($_POST['act_sort'])) {
            $this->error('非法操作');
        }

        $where['ta_id'] = intval($_POST['ta_id']);

        $data = explode(',', strval($_POST['act_sort']));
        foreach ($data as $key => $value) {
            $where['act_id'] = $value;
            M('Activity')->where($where)->save(array('act_sort' => $key));
        }
    }

}