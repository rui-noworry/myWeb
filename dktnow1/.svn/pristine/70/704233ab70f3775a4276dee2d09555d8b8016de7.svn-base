<?php
/**
 * ClassworkAction
 * 练习类
 *
 * 作者:  黄蕊
 * 创建时间: 2013-7-1
 *
 */
class ClassworkAction extends OpenAction {

    public function init() {

        $init['logo'] = '';
        $init['sys_title'] = '';

        $this->ajaxReturn($init);
    }

    // 教师发布练习
    public function publish() {

        extract($_POST['args']);

        // 接收参数
        if (empty($act_id) || empty($a_id) || (empty($c_id) && empty($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 权限验证
        if (!$activity = M('Activity')->where(array('a_id' => $a_id, 'act_type' => 2, 'act_id' => $act_id))->find()) {
            $this->ajaxReturn($this->errCode[7]);
        }

        // 用于修改activity表的已发布班级或群组
        $c_ids = $activity['c_id'];
        $cro_ids = $activity['cro_id'];

        // 处理数据
        $activity['to_id'] = $activity['act_rel'] ? $activity['act_rel'] : '';

        $activity['ap_complete_time'] = $complete_time ? $complete_time : 0;
        $activity['ap_created'] = time();
        $activity['ap_course'] = M('Course')->where(array('a_id' => $a_id, 'co_id' => $activity['co_id']))->getField('co_subject');

        // 如果有绑定班级或是群组的话，刚刚发布的活动记录给添加到活动发布表里去
        if (strval($c_id) != '' || strval($cro_id) != '') {

            // 更新课时发布表相关的c_id和cro_id
            $where['cl_id'] = $activity['cl_id'];
            $where['a_id'] = $this->auth['a_id'];
            $where['s_id'] = $this->auth['s_id'];
            $res = M('ClasshourPublish')->where($where)->field('cp_id,act_id,cl_id')->select();

            // 活动发布对象总人数
            $peoples['act_peoples'] = 0;

            if ($c_id) {

                // 动态
                $action = 2;
                $obj = 2;

                $c_id = explode(',', strval(trim($c_id, ',')));

                // 获取班级人数
                $classInfo = M('Class')->where(array('c_id' => array('IN', $c_id), 's_id' => $this->auth['s_id']))->field('c_id, c_peoples')->select();
                $classInfo = setArrayByField($classInfo, 'c_id');

                // 获取已经发布过的练习
                $hasPublished = M('ActivityPublish')->where(array('act_id' => $act_id, 'c_id' => array('IN', $c_id)))->select();
                $hasPublished = setArrayByField($hasPublished, 'c_id');

                foreach ($c_id as $key => $value) {

                    if (strstr($activity['c_id'], ','.$value.',')) {
                        $data['message'] = '您已经发布过该练习';
                        $data['status'] = 0;
                        $data['ap_id'] = $hasPublished[$value]['ap_id'];
                        break;
                    }

                    $peoples['c_id'] .= ','.$value;
                    $activity['c_id'] = $value;
                    $activity['ap_peoples'] = intval($classInfo[$value]['c_peoples']);
                    $peoples['act_peoples'] += $classInfo[$value]['c_peoples'];
                    $where['c_id'] = $value;

                    $data['ap_id'] = M('ActivityPublish')->add($activity);
                    $data['status'] = 1;

                    foreach ($res as $k => $v) {
                        $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $activity['act_id'] : $activity['act_id'];
                        $save['cp_updated'] = time();
                        $where['cp_id'] = $v['cp_id'];
                        M('ClasshourPublish')->where($where)->save($save);

                     }

                     addTrend($this->auth['a_id'], $this->auth['s_id'], $value, $action, $obj, 0, M('Course')->where(array('co_id' => $activity['co_id']))->getField('co_subject'), strval($activity['act_title']), $data['status']);
                }

            }

            if ($cro_id) {

                $cro_id = explode(',', strval(trim($cro_id, ',')));

                // 获取群组信息
                $crowdAuth = M('AuthCrowd')->where(array('cro_id' => array('IN', $cro_id)))->select();

                $crowdInfo = array();
                foreach ($crowdAuth as $key => $value) {
                    if (in_array($value['cro_id'], $cro_id)) {
                        $crowdInfo[$value['cro_id']]['num'] += 1;
                    }
                }

                // 获取已经发布过的练习
                $hasPublished = M('ActivityPublish')->where(array('act_id' => $act_id, 'cro_id' => array('IN', $cro_id)))->select();
                $hasPublished = setArrayByField($hasPublished, 'cro_id');

                foreach ($cro_id as $key => $value) {

                    if (strstr($activity['cro_id'], ','.$value.',')) {
                        $data['message'] = '您已经发布过该练习';
                        $data['status'] = 0;
                        $data['ap_id'] = $hasPublished[$value]['ap_id'];
                        break;
                    }

                    $peoples['cro_id'] .= ','.$value;

                    $activity['cro_id'] = $value;
                    $activity['ap_peoples'] = $crowdInfo[$value]['num'];
                    $peoples['act_peoples'] += $crowdInfo[$value]['num'];
                    $where['cro_id'] = $value;
                    $data['ap_id'] = M('ActivityPublish')->add($activity);
                    $data['status'] = 1;

                    foreach ($res as $k => $v) {
                        $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $activity['act_id'] : $activity['act_id'];
                        $save['cp_updated'] = time();
                        $where['cp_id'] = $v['cp_id'];
                        M('ClasshourPublish')->where($where)->save($save);
                     }
                }

            }
        }

        if ($data['status']) {
            // 更新发布对象的人数
            $peoples['act_is_published'] = 1;

            if ($c_ids) {
                $peoples['c_id'] .= $c_ids;
            } else {
                $peoples['c_id'] .= ',';
            }

            if ($cro_ids) {
                $peoples['cro_id'] .= $cro_ids;
            } else {
                $peoples['cro_id'] .= ',';
            }

            M('Activity')->where(array('act_id' => $act_id))->save($peoples);
        }

        $this->ajaxReturn($data);

    }

    // 学生获取课时下练习列表 并做练习
    public function studentClassworkList() {

        extract($_POST['args']);

        // 接收参数
        if (empty($cl_id) || empty($a_id) || empty($page_size)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 身份验证
        if ($this->auth['a_type'] != 1) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        if ($c_id != '' || $cro_id != '') {

            if ($c_id) {

                // 验证学生所在班级
                if (!M('ClassStudent')->where(array('a_id' => $a_id, 'c_id' => $c_id, 's_id' => $this->auth['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }

                $where['c_id'] = $c_id;
            }

            if ($cro_id) {

                // 验证学生所在群组
                if (!M('AuthCrowd')->where(array('a_id' => $a_id, 'cro_id' => $cro_id, 's_id' => $this->auth['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }

                $where['cro_id'] = $cro_id;
            }

        } else {

            // 获取我所在的群组ID
            $croidArr = getAuthInfo($this->auth);
            $croidArr = $croidArr['cro_id'];

            // 获取我所在的班级
            $cidArr = getAuthInfo($this->auth);
            $cidArr = $cidArr['c_id'];

            if (!is_array($croidArr)) {
                $croidArr[0] = 0;
            }

            $where['_string'] = '1 != 1';

            if (implode(',', $cidArr)) {
                $where['_string'] .= ' OR c_id IN('.implode(',', $cidArr).')';
            }

            if (implode(',', $croidArr)) {
                $where['_string'] .= ' OR cro_id IN('.implode(',', $croidArr).')';
            }
        }

        $where['cl_id'] = $cl_id;
        $where['s_id'] = $this->auth['s_id'];
        $where['act_type'] = 2;

        // 接收条件
        $p = intval($page) ? intval($page) : 1;

        $ActivityPublish = getListByPage('ActivityPublish', 'ap_id DESC', $where, $page_size, 1, $p);

        // 获取作业答案
        $ActivityData = M('ActivityData')->where(array('a_id' => $this->auth['a_id'], 's_id' => $this->auth['s_id']))->field('ap_id, ad_id, ad_status')->select();

        $ActivityData = setArrayByField($ActivityData, 'ap_id');

        // 组织数据
        foreach ($ActivityPublish['list'] as $key => $value) {

            $data[$key]['cw_id'] = $value['ap_id'];
            $data[$key]['cw_title'] = $value['act_title'];
            $data[$key]['cw_created'] = $value['ap_created'];

            if ($ActivityData[$value['ap_id']]) {
                $data[$key]['ad_id'] = $ActivityData[$value['ap_id']]['ad_id'];
            }
        }

        $this->ajaxReturn($data);

    }

    // 提交练习
    public function insert() {

        extract($_POST['args']);

        // 接收参数
        if (empty($a_id) || empty($cw_id) || empty($cd_answer)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 上传简答题图片
        if ($_FILES['picture_answer']['size'][0]) {

            $file = $_FILES['picture_answer'];

            foreach ($file['name'] as $k => $value) {

                $nameArr = explode('-', $value);

                $_FILES['picture_answer']['name'] = $value;
                $_FILES['picture_answer']['tmp_name'] = $file['tmp_name'][$k];
                $_FILES['picture_answer']['size'] = $file['size'][$k];
                $_FILES['picture_answer']['type'] = $file['type'][$k];
                $_FILES['picture_answer']['error'] = $file['error'][$k];

                $path = C('PICTURE_ANSWER').$nameArr[0] % C('MAX_FILES_NUM').'/'.$nameArr[0].'/'.$a_id % C('MAX_FILES_NUM').'/'.$a_id.'/';

                unlink($path . $value);
                if (!mk_dir($path)) {
                    $this->ajaxReturn('文件夹创建不成功');
                }

                $allowType = C('ALLOW_FILE_TYPE');

                $this->upload($allowType['image'], $path, TRUE, '600', '450', '');
            }
        }

        // 练习统计
        $ad_stat = D('Activity')->stats(stripslashes($cd_answer));

        // 获取我所在的班级(也可能在多个班级取其一)
        $c_id = getAuthInfo($this->auth);
        $c_id = $c_id['c_id'][0];

        $data = $this->studentCommit($c_id, $this->auth['s_id'], $a_id, $cw_id, $cd_answer, $cd_persent, $cd_use_time, $ad_stat);

        $this->ajaxReturn($data);

    }

    // 学生提交练习
    public function studentCommit($c_id, $s_id, $a_id, $ap_id, $ad_answer, $ad_persent = 0, $cd_use_time = 0, $ad_stat = '') {

        // 组织数据
        $data['a_id'] = $a_id;
        $data['ap_id'] = $ap_id;
        $result['status'] = 1;

        if (!$classwork = M('ActivityPublish')->where(array('ap_id' => $ap_id, 's_id' => $s_id, 'act_type' => 2))->find()) {
            $this->ajaxReturn($this->errCode[7]);
            exit;
        }

        if ($classwork['c_id']) {
            // 验证班级学生
            if (!M('ClassStudent')->where(array('c_id' => $classwork['c_id'], 'a_id' => $a_id, 's_id' => $s_id))->find()) {
                $result['status'] = 0;
                $result['message'] = '您没有此练习';
            }
        }

        if ($classwork['cro_id']) {
            // 验证群组学生
            if (!M('AuthCrowd')->where(array('cro_id' => $classwork['cro_id'], 'a_id' => $a_id, 's_id' => $s_id))->find()) {
                $result['status'] = 0;
                $result['message'] = '您没有此练习';
            }
        }



        // 获取作业答案信息
        $classworkData = M('ActivityData')->where($data)->find();

        $data['ad_status'] = 1;
        $data['cl_id'] = $classwork['cl_id'];
        $data['co_id'] = $classwork['co_id'];
        $data['l_id'] = $classwork['l_id'];
        $data['ad_stat'] = $ad_stat;
        $data['ap_id'] = $ap_id;

        $data['ad_use_time'] = intval($cd_use_time);
        $data['ad_answer'] = stripslashes($ad_answer);

        if ($classworkData['ad_id']) {

            $result['status'] = 0;
            $result['message'] = '您已经提交过此练习';

        } else {

            $data['ad_created'] = time();
            M('ActivityData')->add($data);

            // 提交
            M('ActivityPublish')->where(array('ap_id' => $ap_id))->setInc('ap_count');

            // 添加动态
            addTrend($a_id, $s_id, $c_id, 1, 2, 0, $classwork['ap_course'], $classwork['act_title'], $classwork['act_id']);

            // 统计ActivityPublish, Activity, Topic 表
            $ad_stat = get_object_vars(json_decode($ad_stat));

            // 活动发布表
            // 如果还没有被统计
            if (!$classwork['ap_stat'] || is_null($classwork['ap_stat'])) {

                $topic = explode(',', $classwork['to_id']);

                foreach ($topic as $key => $value) {
                    $stat[$value] = 0;
                }

            } else {
                $stat = json_decode($classwork['ap_stat'], TRUE);
            }

            // 为活动表处理做准备
            $tmp = $stat;

            // 正确的题目加1
            foreach ($stat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $stat[$key] = $value + 1;
                }
            }

            $actPublish['ap_id'] = $ap_id;
            $actPublish['ap_stat'] = json_encode($stat);

            // 更新活动发布表
            M('ActivityPublish')->save($actPublish);

            // 活动表
            $activity = M('Activity')->where(array('act_id' => $classwork['act_id']))->field('act_id, act_stat')->find();

            // 如果没有被统计过
            if (!$activity['act_stat'] || is_null($actvity['act_stat'])) {
                $activityStat = $tmp;
            } else {
                $activityStat = get_object_vars(json_decode($activity['act_stat'], TRUE));
            }

            // 正确的题目加1
            foreach ($activityStat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $activityStat[$key] = $value + 1;
                }
            }

            $actStat['act_id'] = $activity['act_id'];
            $actStat['act_stat'] = json_encode($activityStat);

            // 更新活动表
            M('Activity')->save($actStat);

            // 题目表
            $topics = M('Topic')->where(array('to_id' => array('IN', implode(',', array_keys($ad_stat)))))->field('to_id, to_peoples')->select();
            $topics = setArrayByField($topics, 'to_id');

            foreach ($ad_stat as $key => $value) {
                if ($value == 1) {
                    M('Topic')->where(array('to_id' => $key))->setInc('to_peoples');
                }
            }
        }

        return $result;

    }

    // 教师批改学生练习
    public function correct() {

        extract($_POST['args']);

        // 接收参数
        if (empty($a_id) || empty($cw_id) || empty($stu_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 验证教师权限
        $classwork = M('ActivityPublish')->where(array('ap_id' => $cw_id, 'a_id' => $a_id, 'act_type' => 2, 's_id' => $this->auth['s_id']))->find();
        if (!$classwork) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        // 验证此活动是否为教师创建
        $activity = M('Activity')->where(array('act_id' => $classwork['act_id'], 'a_id' => $a_id, 's_id' => $this->auth['s_id']))->find();
        if (!$activity) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        // 练习ID
        $data['cw_id'] = $cw_id;

        // 学生ID
        $data['a_id'] = $stu_id;

        $data['c_id'] = intval($classwork['c_id']);
        $data['cro_id'] = intval($classwork['cro_id']);

        // 获取学生答案ID
        $activityData = M('ActivityData')->where(array('ap_id' => $cw_id, 'a_id' => $stu_id))->field('ad_id, ad_answer, ad_persent, ad_created, ad_updated')->find();

        if ($activityData) {

            // 获取简答题图片
            $topic = explode(',', $activity['act_rel']);

            $pictureAnwser = C('PICTURE_ANSWER');
            $maxFilesNum = C('MAX_FILES_NUM');

            $arrFile = array();

            foreach ($topic as $tKey => $tValue) {
                $folder = $pictureAnwser . ($tValue % $maxFilesNum) . '/' . $tValue . '/' . ($stu_id % $maxFilesNum) . '/' . $stu_id . '/';

                if (is_dir($folder)) {
                    $arrFile[$tValue] = getFiles($folder);

                    foreach ($arrFile[$tValue] as $afKey => $afValue) {
                        $arrFile[$tValue][$afKey] = turnTpl($afValue);
                    }
                }
            }

            $data['picture_answer'] = $arrFile;

            $data['cd_answer'] = $activityData['ad_answer'];
            $data['cd_persent'] = $activityData['ad_persent'];
            $data['cd_created'] = intval($activityData['ad_created']);
            $data['cd_updated'] = intval($activityData['ad_updated']);
        }

        $this->ajaxReturn($data);

    }

    // 获取作业列表
    public function lists() {

        // 接收参数
        extract($_POST['args']);

        $auth = $this->auth;

        if ((empty($c_id) && empty($cro_id)) || empty($a_id) || $auth['a_type'] != 1 || empty($page_size)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        $where['act_type'] = 2;

        if ($c_id) {
            $where['c_id'] = $c_id;
        }

        if ($cro_id) {
            $where['cro_id'] = $cro_id;
        }

        if ($h_course) {
            $where['ap_course'] = $h_course;
        }

        $page = intval($page) ? intval($page) : 1;

        $lists = getListByPage('ActivityPublish', 'ap_complete_time DESC', $where, $page_size, 1, $page);

        preg_match_all('/\/(\d+)/', $lists['page'], $match);
        $lists['page'] = $match[1][0];

        $this->ajaxReturn($lists);
    }


    // 学生做练习
    public function doClasswork() {

        extract($_POST['args']);

        $ap_id = intval($ap_id);
        $a_id = intval($a_id);

        // 接收参数
        if (empty($ap_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 验证数据有效性
        $classwork = M('ActivityPublish')->where(array('ap_id' => $ap_id, 's_id' => $this->auth['s_id'], 'act_type' => 2))->find();

        if (!$classwork) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        if ($classwork['c_id']) {
            // 验证班级学生
            if (!M('ClassStudent')->where(array('c_id' => $classwork['c_id'], 'a_id' => $this->auth['a_id'], 's_id' => $this->auth['s_id']))->find()) {
                $this->ajaxReturn($this->errCode[4]);
                exit;
            }
        }

        if ($classwork['cro_id']) {
            // 验证群组学生
            if (!M('AuthCrowd')->where(array('cro_id' => $classwork['cro_id'], 'a_id' => $this->auth['a_id'], 's_id' => $this->auth['s_id']))->find()) {
                $this->ajaxReturn($this->errCode[4]);
                exit;
            }
        }

        // 获取活动信息
        $detail = D('Activity')->detail($a_id, $classwork['act_id'], $classwork['c_id'], $classwork['cro_id'], 1);
        $data['status'] = $detail['status'];
        $data['info'] = $detail['info'];

        $classworkData = M('ActivityData')->where(array('ap_id' => $ap_id))->find();

        if ($classworkData) {

            // 获取简答题图片
            $topic = explode(',', $detail['info']['list']['act_rel']);

            $pictureAnwser = C('PICTURE_ANSWER');
            $maxFilesNum = C('MAX_FILES_NUM');

            $arrFile = array();

            foreach ($topic as $tKey => $tValue) {
                $folder = $pictureAnwser . ($tValue % $maxFilesNum) . '/' . $tValue . '/' . ($a_id % $maxFilesNum) . '/' . $a_id . '/';

                if (is_dir($folder)) {
                    $arrFile[$tValue] = getFiles($folder);

                    sort($arrFile[$tValue]);

                    foreach ($arrFile[$tValue] as $afKey => $afValue) {
                        $arrFile[$tValue][$afKey] = turnTpl($afValue);
                    }
                }
            }

            $data['picture_answer'] = $arrFile;

            $data['ad_id'] = $classworkData['ad_id'];
            $data['ad_answer'] = $classworkData['ad_answer'];
        }


        $this->ajaxReturn($data);
    }

    // 获取文件目录下的所有文件名
    public function file_lists($folder) {

        //打开目录
        $fp = opendir($folder);

         //阅读目录
        while(false != $file = readdir($fp)) {

            //列出所有文件并去掉'.'和'..'
            if($file != '.' && $file != '..') {

                $file = "$file";

                //赋值给数组
                $arr_file[] = $folder.$file;

            }
        }

        return $arr_file;
    }

    // 删除简答题图片
    public function deletePictureAnswer() {

        extract($_POST['args']);

        $filename = strval($filename);
        $a_id = intval($a_id);

        // 接收参数
        if (empty($filename) || empty($a_id) || $this->auth['a_type'] != 1) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        $fileArr = explode('-', $filename);
        $to_id = $fileArr[0];

        // 数据验证
        if (M('Topic')->where(array('to_id' => $to_id))->getField('to_type') != 5) {
            $this->ajaxReturn($this->errCode[7]);
            exit;
        }

        $path = C('PICTURE_ANSWER').$to_id % C('MAX_FILES_NUM').'/'.$to_id.'/'.$a_id % C('MAX_FILES_NUM').'/'.$a_id.'/'.$filename;

        $res = unlink($path);

        if ($res) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        $this->ajaxReturn($data);

    }

    // 上传
    public function upload($allowType, $savePath, $thumb = FALSE, $width = '', $height = '', $prefix = '', $maxSize='', $remove = FALSE) {

        import("@.ORG.Net.UploadFile");

        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = empty($maxSize)?C('MAX_UPLOAD_FILE_SIZE'):$maxSize;

        if ($thumb) {
            $upload->thumb = $thumb;
            $upload->thumbPrefix = $prefix;
            $upload->thumbMaxWidth = $width;
            $upload->thumbMaxHeight = $height;
            $upload->thumbRemoveOrigin = $remove;
        }

        //设置上传文件类型
        $upload->allowExts = $allowType;
        //设置附件上传目录
        $upload->savePath = $savePath;

        $upload->saveRule = '';

        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $info = $upload->getUploadFileInfo();
            return $info[0]['savename'];
        }
    }

    // 练习统计
    public function stats() {

        extract($_POST['args']);

        $auth = $this->auth;

        // 接收参数
        if (empty($act_id) || empty($a_id) || (empty($c_id) && empty($cro_id)) || $auth['a_type'] != 2) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        if ($c_id) {

            if (!$res = M('ActivityPublish')->where(array('act_id' => $act_id, 'a_id' => $a_id, 'act_type' => 2, 'c_id' => $c_id))->find()) {
                $this->ajaxReturn($this->errCode[7]);
                exit;
            }

            // 获取班级下的所有学生
            $stuIds = M('ClassStudent')->where(array('c_id' => $c_id, 's_id' => $this->auth['s_id']))->getField('a_id', TRUE);
        }

        if ($cro_id) {

            if (!$res = M('ActivityPublish')->where(array('act_id' => $act_id, 'a_id' => $a_id, 'act_type' => 2, 'cro_id' => $cro_id))->find()) {
                $this->ajaxReturn($this->errCode[7]);
                exit;
            }

            // 获取群组下的所有学生
            $stuIds = M('AuthCrowd')->where(array('cro_id' => $cro_id, 's_id' => $this->auth['s_id']))->getField('a_id', TRUE);

        }

        $data = D('Activity')->teacherStats($stuIds, $res);

        ksort($data);

        $i = 0;

        foreach ($data as $dk => $dv) {

            $i ++;

            $stat[$dk]['to_id'] = $dk;
            $stat[$dk]['peoples'] = $dv;
            $stat[$dk]['num'] = $i;
        }

        $this->ajaxReturn((array)$stat);
    }

    // 教师查看某个班级下某个练习的学生
    public function listsAuth() {

        // 接收参数
        extract($_POST['args']);

        if (empty($ap_id) || (empty($c_id) && empty($cro_id)) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 检查是否为创建者
        if (!$res = M('ActivityPublish')->where(array('ap_id' => $ap_id, 'a_id' => $a_id, 'act_type' => 2))->find()) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        // 获取班级里的学生
        if ($c_id) {
            $student = M('ClassStudent')->where(array('c_id' => $c_id, 's_id' => $this->auth['s_id']))->select();
        }

        // 获取群组中的学生
        if ($cro_id) {
            $student = M('AuthCrowd')->where(array('cro_id' => $cro_id, 's_id' => $this->auth['s_id']))->select();
        }

        // 获取用户信息
        $student = getDataByArray('Auth', $student, 'a_id', 'a_id, a_nickname, a_type, a_sex');

        // 获取学生答案
        $data = M('ActivityData')->where(array('ap_id' => $ap_id, 'a_id' => array('IN', implode(',', getValueByField($student, 'a_id')))))->field('a_id, ad_id, ad_status')->select();

        foreach ($data as $key => $value) {
            $student[$value['a_id']]['hd_id'] = $value['ad_id'];
            $student[$value['a_id']]['hd_status'] = $value['ad_status'];
        }

        // 获取学生头像
        foreach ($student as $key => $value) {
            $student[$key]['a_avatar'] = turnTpl(getAuthAvatar($value['a_avatar'], $value['a_type'], $value['a_sex']));
            $student[$key]['h_id'] = $ap_id;

            if (!$value['hd_status']) {
                $student[$key]['hd_status'] = 0;
            }
        }

        sort($student);

        // 已提交作业的学生先列出，数组排序
        foreach ($student as $sk => $sv) {
            $hd_status[$sk] = $sv['hd_status'];
        }

        array_multisort($hd_status, SORT_DESC, $student);

        $this->ajaxReturn($student);
    }
}
?>