<?php
/**
 * ActivityDataAction
 * 学生答案模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-5-28
 *
 */
class ActivityDataAction extends BaseAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();
        $this->template = strtolower(ACTION_NAME).$this->authInfo['a_type'];
    }

    // 学生做作业
    public function doActivity() {

        if ($this->authInfo['a_type'] != 1) {
            $this->redirect('/Homework');
        }

        $ap_id = $where['ap_id'] = intval($_REQUEST['ap_id']);
        $ad_id = intval($_REQUEST['ad_id']);

        // 接收参数
        if (!$ap_id) {
            $this->redirect('/Homework');
        }

        // 获取作业信息
        $activityPublish = M('ActivityPublish')->where($where)->find();

        if (!$activityPublish) {
            $this->error('教师暂未发布');
        }

        $c_id = $activityPublish['c_id'];
        $cro_id = $activityPublish['cro_id'];
        $this->maxSize = intval(ini_get('upload_max_filesize'));
        if ($c_id) {

            // 验证班级学生
            if (!M('ClassStudent')->where(array('c_id' => $c_id, 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find()) {
                $this->error('非法操作');
            }
            $where['c_id'] = $c_id;
        }

        if ($cro_id) {

            // 验证群组学生
            if (!M('AuthCrowd')->where(array('cro_id' => $cro_id, 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find()) {
                $this->error('非法操作');
            }
            $where['cro_id'] = $cro_id;
        }

        // 查询作业答案
        if ($ad_id) {
            $activityData = M('ActivityData')->where(array('ad_id' => $ad_id, 'a_id' => $this->authInfo['a_id']))->find();
            $answer = json_decode($activityData['ad_answer'], TRUE);
        }

        $answerShow = 0;

        // 若作业已完成，显示作业正确答案
        if ($activityData['ad_status'] == 4) {
            $answerShow = 1;
        }

        // 选项
        $tit = array('A', 'B', 'C', 'D', 'E', 'F');

        // 获取作业题目
        $list = M('Topic')->where(array('to_id' => array('IN', $activityPublish['to_id'])))->select();

        foreach ($list as $key => $value) {

            $list[$key]['to_title'] = htmlspecialchars_decode($value['to_title']);

            // 单选
            if ($list[$key]['to_type'] == 1 || $list[$key]['to_type'] == 4) {
                $to_answer = json_decode($value['to_answer'], TRUE);
                $list[$key]['to_answer'] = $to_answer[0];
            }

            // 多选
            if ($list[$key]['to_type'] == 2) {
                $to_answer = json_decode($value['to_answer'], TRUE);
                $list[$key]['to_answer'] = explode(',', $to_answer[0]);
            }

            // 填空
            if ($list[$key]['to_type'] == 3) {
                $list[$key]['to_answer'] = json_decode($value['to_answer']);
            }

            // 简答题
            if ($list[$key]['to_type'] == 5) {
                $list[$key]['to_answer'] = json_decode($value['to_answer']);
                $list[$key]['to_answer'] = $list[$key]['to_answer'][0];
            }

            $list[$key]['stu_answer'] = -1;

            // 处理答案
            if ($answer[$value['to_id']] != '') {
                if ($list[$key]['to_type'] == 3 || $list[$key]['to_type'] == 5) {
                    $list[$key]['stu_answer'] = json_decode($answer[$value['to_id']]);
                } else {
                    $list[$key]['stu_answer'] = $answer[$value['to_id']];
                }
            }

            $list[$key]['to_option'] = explode(',', $value['to_option']);

            // 显示正确答案
            if ($answerShow == 1) {

                // 对比学生答案和正确答案判断正误
                if ($list[$key]['to_type'] == 2) {
                    $list[$key]['ex_er'] = ($list[$key]['stu_answer'] == implode(',', $list[$key]['to_answer'])) ? 1 : 0;
                }  else {
                    $list[$key]['ex_er'] = ($list[$key]['stu_answer'] == $list[$key]['to_answer']) ? 1 : 0;
                }

                // 单选
                if ($value['to_type'] == 1) {
                    $list[$key]['exact'] = $tit[$list[$key]['to_answer']];
                }

                // 多选
                if ($value['to_type'] == 2) {
                    foreach ($list[$key]['to_answer'] as $tKey => $tValue) {
                        $tmp[$tKey] = $tit[$tValue];
                    }
                    $list[$key]['exact'] = implode(',', $tmp);
                }

                // 填空
                if ($value['to_type'] == 3) {
                    foreach ($list[$key]['to_answer'] as $eKey => $eValue) {
                        if ($eValue != $list[$key]['stu_answer'][$eKey]) {
                            $list[$key]['exact'] .= ',<span class="error">' . $eValue . '</span>';
                        } else {
                            $list[$key]['exact'] .= ',<span class="exact">' . $eValue . '</span>';
                        }
                    }
                    $list[$key]['exact'] = substr($list[$key]['exact'], 1);
                }

                // 判断
                if ($value['to_type'] == 4) {
                    $tmp = $list[$key]['to_answer'] == 1 ? 'ok' : 'err';
                    $list[$key]['exact'] = '<img width="25" height="25" border="0" src="/Public/Images/Home/'.$tmp.'.png">';
                }

                // 简答题
                if ($value['to_type'] == 5) {

                    $list[$key]['exact'] = $list[$key]['to_answer'];

                    // 获取简答题图片
                    $folder = C('PICTURE_ANSWER').$value['to_id'] % C('MAX_FILES_NUM').'/'.$value['to_id'].'/'.$this->authInfo['a_id'] % C('MAX_FILES_NUM').'/'.$this->authInfo['a_id'].'/';

                    $picture_answer = D('Activity')->file_lists($folder);

                    if ($picture_answer) {

                        foreach ($picture_answer as $pk => $pv) {

                            $pic_answer[$pk]['url'] = turnTpl($pv);
                            $pic_answer[$pk]['filename'] = basename($pv);
                        }

                        $list[$key]['picture_answer'] = $pic_answer;
                    }

                }

            }

        }



        // 获取老师已上传的附件
        $ar_id = M('ActivityAttachment')->where(array('s_id' => $this->authInfo['s_id'], 'act_id' => $activityPublish['act_id']))->getField('ar_id');
        if ($ar_id) {
            $lists = listAttachments($ar_id);
            $this->uploadFiles = $lists;
        }

        $this->assign('homework', $activityPublish);
        $this->assign('homeworkData', $activityData);
        $this->assign('tit', $tit);
        $this->assign('lists', $list);
        $this->assign('answerShow', $answerShow);
        $this->display();
    }

    // 学生预览活动
    public function activity() {

        $ap_id = $where['ap_id'] = intval($_REQUEST['ap_id']);
        $type = intval($_REQUEST['type']);

        if (!$ap_id || !$type) {
            $this->error('参数错误');
        }

        // 获取活动信息
        $activity = M('ActivityPublish')->where($where)->field('act_id, ap_course, c_id, cro_id, act_title, to_id')->find();

        if ($this->authInfo['a_type'] == 1) {

            if ($activity['c_id']) {
                // 验证班级学生
                if (!M('ClassStudent')->where(array('c_id' => $activity['c_id'], 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find()) {
                    $this->error('您无权限操作');
                }
            }

            if ($activity['cro_id']) {
                // 验证群组学生
                if (!M('AuthCrowd')->where(array('cro_id' => $activity['cro_id'], 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find()) {
                    $this->error('您无权限操作');
                }
            }

        }

        if ($this->authInfo['a_type'] == 2) {

            if ($activity['c_id']) {

                // 首先验证是不是班主任，若不是再判断是不是任课教师
                if (!M('Class')->where(array('a_id' => $this->authInfo['a_id'], 'c_id' => $activity['c_id'], 's_id' => $this->authInfo['s_id']))->find()) {

                    if (!M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'c_id' => $activity['c_id'], 's_id' => $this->authInfo['s_id'], 'cst_course' => $activity['ap_course']))->find()) {
                        $this->error('您无权限操作');
                    }
                }
            }

            if ($activity['cro_id']) {

                if (!M('Crowd')->where(array('cro_id' => $activity['cro_id'], 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find()) {
                    $this->error('您无权限操作');
                }
            }
        }

        // 活动类型
        if ($type == 3 || $type == 5) {
          $activity['act_note'] = M('Activity')->where(array('act_id' => $activity['act_id']))->getField('act_note');
          $activity['act_note'] = strip_tags(htmlspecialchars_decode($activity['act_note']));
        }

        // 链接类型
        if ($type == 4) {
            $links = M('Link')->where(array('li_id' => array('IN', $activity['to_id'])))->select();
            $this->links = $links;
        }

        // 拓展活动
        if ($type == 5) {

            // 已添加的资源
            $resource = M('AuthResource')->where(array('ar_id' => array('IN', $activity['to_id'])))->order('ar_id DESC')->select();

            foreach ($resource as $key => $value) {
                $resource[$key]['ar_upload'] = getResourceImg($value);
            }

            $this->resource = $resource;
        }

        $this->assign('activity', $activity);
        $this->display(strtolower(ACTION_NAME . $type));

    }

    // 学生查看链接
    public function links() {

        $ap_id = $where['ap_id'] = intval($_REQUEST['ap_id']);

        // 获取活动信息
        $activity = M('ActivityPublish')->where($where)->field('act_id, act_title, to_id')->find();

        $this->assign('activity', $activity);
        $this->display();
    }

    // 学生提交
    public function insert() {

        if ($this->authInfo['a_type'] != 1) {
            $this->redirect('/Homework');
        }

        // 接收参数
        $ap_id = intval($_POST['ap_id']);

        foreach ($_POST['to_answer'] as $key => $value) {
            if (intval($_POST['to_type'][$key]) == 3 || intval($_POST['to_type'][$key]) == 5) {
                $_POST['to_answer'][$key] = json_encode($value);
            }
        }

        // 获取活动发布信息
        $ActivityPublish = M('ActivityPublish')->where(array('ap_id' => $ap_id))->find();

        // 获取我所在的班级, 若在多个班级取其一
        $c_id = $this->authInfo['c_id'][0];

        // 判断学生是否上传附件
        if (intval($_POST['uploader_count']) > 0) {

            // 用户上传一个资源加一分
            M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

            // 把该活动所属课程标签添加或是更新到资源标签表，并以数组形式返回相关的标签ID
            $course = M('Course')->where(array('co_id' => $ActivityPublish['co_id']))->find();

            foreach ($_SESSION['result'] as $va) {
                $rta_id = turnIdToWord($course, 'ResourceTag');
            }
            $rta_id = ',' . implode(',', $rta_id) . ',';

            // 读取配置文件，看看文档和视频是否需要自动转码
            $documentCode = C('AUTO_TRANS_DOCUMENT');
            $videoCode = C('AUTO_TRANS_VIDEO');

            // 根据上传的资源数量，依次写入我的资源表
            if ($_SESSION['result']) {

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

                    if (in_array($value['m_id'], array(1,3))) {
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

                // 销毁该session
                unset($_SESSION['result']);

                // 依据发布活动ID查询活动附件表，有数据记录的话就是更新，否则就是写入
                $activityAttachment['s_id'] = $this->authInfo['s_id'];
                $activityAttachment['a_id'] = $this->authInfo['a_id'];
                $activityAttachment['ap_id'] = $ActivityPublish['ap_id'];
                $infos = M('ActivityAttachment')->where($activityAttachment)->find();
                if ($infos) {
                    $saveDatas['ar_id'] = $infos['ar_id'] . ltrim($ar_id, ',');
                    $saveDatas['aat_updated'] = time();
                    M('ActivityAttachment')->where(array('aat_id' => $infos['aat_id']))->save($saveDatas);
                } else {
                    $activityAttachment['co_id'] = $ActivityPublish['co_id'];
                    $activityAttachment['l_id'] = $ActivityPublish['l_id'];
                    $activityAttachment['cl_id'] = $ActivityPublish['cl_id'];
                    $activityAttachment['ta_id'] = $ActivityPublish['ta_id'];
                    $activityAttachment['ar_id'] = $ar_id;
                    $activityAttachment['act_type'] = $ActivityPublish['act_type'];
                    $activityAttachment['aat_created'] = time();
                    M('ActivityAttachment')->add($activityAttachment);
                }
            }
        }

        // 作业
        if ($ActivityPublish['act_type'] == 1) {

            $result = D('Homework')->studentCommit($c_id, $this->authInfo['s_id'], $this->authInfo['a_id'], $ap_id, addslashes(json_encode($_POST['to_answer'])), intval($_POST['ad_persent']),  intval($_POST['ad_storage']));

            if ($result['status'] == 0) {
                $this->assign('jumpUrl', '/Homework/index');
                $this->error($result['message']);
            }

            $this->redirect('/Homework');
        }

        // 练习
        if ($ActivityPublish['act_type'] == 2) {

            $result = D('Classwork')->studentCommit($c_id, $this->authInfo['s_id'], $this->authInfo['a_id'], $ap_id, addslashes(json_encode($_POST['to_answer'])), intval($_POST['ad_persent']), intval($_POST['ad_storage']));

            if ($result['message']) {
                $this->assign('jumpUrl', '/Homework');
                $this->error($result['message']);
            }

            $this->redirect('/Classwork');
        }
    }

    // 获取学生作业答案
    public function getActivityData() {

        // 接收参数
        $ajax = intval($_POST['ajax']);
        $a_id = intval($_POST['a_id']);

        // 班级或群组ID
        $id = intval($_POST['id']);

        // 该字段用来区分班级或群组
        $field = trim($_POST['field']);

        $act_id = intval($_POST['act_id']);

        if (!$res = M('ActivityPublish')->where(array('a_id' => $this->authInfo['a_id'], $field => $id, 'act_id' => $act_id))->find()) {
            $this->error('您没有权限');
        }

        $ap_id = $res['ap_id'];

        $data = M('ActivityData')->where(array('a_id' => $a_id, 'ap_id' => $ap_id))->find();
        $data['ad_answer'] = json_decode($data['ad_answer'], TRUE);
        $data['ad_shortanswer'] = json_decode($data['ad_shortanswer'], TRUE);
        $data['ad_stat'] = json_decode($data['ad_stat']);

        foreach ($data['ad_answer'] as $key => $value) {
            if (json_decode($value)) {
                $data['ad_answer'][$key] = json_decode($value);
            }
        }

        // 获取学生已上传的附件
        $ar_id = M('ActivityAttachment')->where(array('s_id' => $this->authInfo['s_id'], 'ap_id' => $data['ap_id']))->getField('ar_id');
        if ($ar_id) {
            $data['files'] = listAttachments($ar_id);
        }

        if ($ajax) {
            if ($data) {
                $this->success($data);
            } else {
                $this->error();
            }
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
           exit('不能上传空文件');
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
            exit('上传文件类型不支持');
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

        if (in_array($result['m_id'], array(1,3))) {

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


    // 删除简答题图片
    public function del_picAnswer() {

        // 接收参数
        $filename = strval($_POST['filename']);

        $pathInfo = explode('-', $filename);

        $path = C('PICTURE_ANSWER').$pathInfo[0] % C('MAX_FILES_NUM').'/'.$pathInfo[0].'/'.$this->authInfo['a_id'] % C('MAX_FILES_NUM').'/'.$this->authInfo['a_id'].'/'.$filename;

        $res = unlink($path);

        if ($res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    // 学生查看讨论
    public function talk() {

        if ($this->authInfo['a_type'] == 1) {

            if (!intval($_REQUEST['ap_id'])) {
                $this->error('无效数据');
            }

            $this->activity = M('ActivityPublish')->where(array('ap_id' => intval($_REQUEST['ap_id'])))->find();
        }

        if ($this->authInfo['a_type'] == 2) {

            if (!intval($_REQUEST['act_id'])) {
                $this->error('无效数据');
            }

            $this->activity = M('Activity')->where(array('act_id' => intval($_REQUEST['act_id'])))->find();
        }

        $this->display($this->template);
    }

    // 获取讨论列表
    public function lists() {

        // 置顶
        if (intval($_POST['at_id']) && intval($_POST['act_id'])) {
            M('ActivityTalk')->where(array('act_id' => intval($_POST['act_id'])))->save(array('at_is_top' => 0));
            M('ActivityTalk')->where(array('at_id' => intval($_POST['at_id'])))->save(array('at_is_top' => 1));
        }

        // 分页
        $p = intval($_REQUEST['p']) ? intval($_REQUEST['p']) : 1;

        if (intval($_REQUEST['ap_id'])) {
            $map['act_id'] = M('ActivityPublish')->where(array('ap_id' => intval($_REQUEST['ap_id'])))->getField('act_id');
        }

        if (intval($_REQUEST['act_id'])) {
            $map['act_id'] = intval($_REQUEST['act_id']);
        }

        $map['s_id'] = $this->authInfo['s_id'];

        $result = getListByPage('ActivityTalk', 'at_is_top DESC, at_id DESC', $map, '', 1, $p);

        $authInfo = getDataByArray('Auth', $result['list'], 'a_id', 'a_id, a_nickname');

        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['a_nickname'] = $authInfo[$value['a_id']]['a_nickname'];
        }

        echo json_encode($result);
    }

    // 学生或老师发表讨论
    public function insertTalk() {

        if ($this->authInfo['a_type'] == 1) {

            $ap_id = intval($_POST['ap_id']);

            // 数据验证
            if (!$activityPublish = M('ActivityPublish')->where(array('ap_id' => $ap_id, 's_id' => $this->authInfo['s_id']))->field('act_id, act_title, co_id, cl_id, l_id, c_id, cro_id')->find()) {

                $this->error('没有此数据');

            }

            // 权限验证
            if ($activityPublish['c_id']) {

                if (!M('ClassStudent')->where(array('c_id' => $activityPublish['c_id'], 'a_id' => $this->authInfo['a_id']))->count()) {
                    $this->error('您无权限操作');
                }
            }

            if ($activityPublish['cro_id']) {

                if (!M('AuthCrowd')->where(array('cro_id' => $activityPublish['cro_id'], 'a_id' => $this->authInfo['a_id']))->count()) {
                    $this->error('您无权限操作');
                }
            }

            $data['co_id'] = $activityPublish['co_id'];
            $data['cl_id'] = $activityPublish['cl_id'];
            $data['l_id'] = $activityPublish['l_id'];
            $data['act_id'] = $activityPublish['act_id'];
            $data['ap_id'] = $ap_id;
            $data['at_content'] = strval($_POST['at_content']);
            $data['a_id'] = $this->authInfo['a_id'];
            $data['s_id'] = $this->authInfo['s_id'];
            $data['at_created'] = time();

        }

        if ($this->authInfo['a_type'] == 2) {

            $act_id = intval($_POST['act_id']);

            // 数据验证
            if (!$activity = M('Activity')->where(array('act_id' => $act_id, 's_id' => $this->authInfo['s_id']))->field('act_id, act_title, co_id, cl_id, l_id')->find()) {

                $this->error('没有此数据');
            }

            $data['co_id'] = $activity['co_id'];
            $data['cl_id'] = $activity['cl_id'];
            $data['l_id'] = $activity['l_id'];
            $data['act_id'] = $activity['act_id'];
            $data['act_id'] = $act_id;
            $data['at_content'] = strval($_POST['at_content']);
            $data['a_id'] = $this->authInfo['a_id'];
            $data['s_id'] = $this->authInfo['s_id'];
            $data['at_created'] = time();
        }

        $result['at_id'] = M('ActivityTalk')->add($data);
        $result['a_nickname'] = $this->authInfo['a_nickname'];

        echo json_encode($result);

    }
}
?>