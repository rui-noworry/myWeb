<?php
/**
 * HomeworkAction
 * 作业模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-27
 *
 */
class HomeworkAction extends BaseAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();

        if (!$this->authInfo['s_id']) {
            $this->redirect('/Index');
        }

        // 获取我所在的学校
        $school = loadCache('school');
        $this->school = $school[$this->authInfo['s_id']]['s_name'];
        $this->template = strtolower(ACTION_NAME).$this->authInfo['a_type'];
    }

    public function stat() {

        // 接收参数
        $ap_id = intval($_REQUEST['ap_id']);

        if (!$ap_id) {
            $this->error('非法操作');
        }

        $ap = M('ActivityPublish')->where(array('ap_id' => $ap_id))->find();

        if (!$ap) {
            $this->error('非法操作');
        }

        $c_id = intval($ap['c_id']);
        $cro_id = intval($ap['cro_id']);

        // 获取班级里的学生
        if ($c_id) {
            $student = M('ClassStudent')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->select();
            $class = M('Class')->where(array('c_id' => $c_id))->find();
            $name = replaceClassTitle($class['s_id'], $class['c_type'], $class['c_grade'], $class['c_title'], $class['c_is_graduation']);
        }

        // 获取群组中的学生
        if ($cro_id) {
            $student = M('AuthCrowd')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->select();
            $name = M('Crowd')->where(array('cro_id' => $cro_id))->getField('cro_title');
        }

        // 获取用户信息
        $student = getDataByArray('Auth', $student, 'a_id', 'a_id, a_nickname, a_type, a_sex');

        // 获取学生答案
        $data = M('ActivityData')->where(array('ap_id' => $ap_id, 'a_id' => array('IN', implode(',', getValueByField($student, 'a_id')))))->field('a_id,ad_id,ad_status,ad_score')->select();

        $sort = sortByField($data, 'ad_score');
        $sort = setArrayByField($sort, 'a_id');

        // 获取学生头像
        foreach ($student as $key => $value) {
            $student[$key]['a_avatar'] = getAuthAvatar($value['a_avatar'], $value['a_type'], $value['a_sex']);
            $student[$key]['ad_id'] = intval($sort[$value['a_id']]['ad_id']);
            $student[$key]['ad_status'] = intval($sort[$value['a_id']]['ad_status']);

            if ($sort[$value['a_id']]) {
                $sort[$value['a_id']]['a_nickname'] = $value['a_nickname'];
            }
        }
        $this->upper = $sort;

        $lower[] = array_pop($sort);
        $lower[] = array_pop($sort);
        $lower[] = array_pop($sort);

        $this->lower = $lower;

        $topic = M('Topic')->where(array('to_id' => array('IN', $ap['to_id'])))->field('to_id,to_title')->select();

        $statTmp = get_object_vars(json_decode($ap['ap_stat']));

        foreach ($topic as $tKey => $tValue) {
            $topic[$tKey]['stat'] = $statTmp[$tValue['to_id']];
            $topic[$tKey]['person'] = $statTmp[$tValue['to_id']] / $ap['ap_peoples'] * 100;
        }

        $ap['act_title'] = $name . $ap['act_title'];

        $this->topic = $topic;
        $this->student = $student;
        $this->ap = $ap;
        $this->display();
    }
    public function index() {

        // 学生
        if ($this->authInfo['a_type'] == 1) {

            // 获取我所在的班级ID
            $cidArr = $this->authInfo['c_id'];

            // 班级数据
            $class = M('Class')->where(array('c_id' => array('IN', implode(',', $cidArr))))->field('c_id, c_title, c_grade, c_type, c_is_graduation')->select();

            // 转换班级名称
            foreach ($class as $key => $value) {
                $c_grade = YearToGrade($value['c_grade'], $this->authInfo['s_id']);
                $class[$key]['c_name'] = replaceClassTitle($this->authInfo['s_id'], $value['c_type'], $c_grade, $value['c_title'], $value['c_is_graduation']);
            }

            // 获取我所在的群组ID
            $croidArr = M('AuthCrowd')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->select();

            $crowdInfo = getDataByArray('Crowd', $croidArr, 'cro_id', 'cro_id, cro_title');

            $this->assign('course', $course);
            $this->assign('class', $class);
            $this->assign('crowd', $crowdInfo);

        }

        // 老师
        if ($this->authInfo['a_type'] == 2) {

            $classCrowdInfo = $this->getClassCrowdInfo();

            // 获取我所教的班级数据
            $class = $classCrowdInfo['class'];

            // 获取我所建的群组数据
            $crowd = $classCrowdInfo['crowd'];

            // 获取时间段内的配置常量
            $partTimeHomework = C('ACTIVITYLIST_WITH_IN');

            $this->assign('partTimeHomework', $partTimeHomework);
            $this->assign('class', $class);
            $this->assign('crowd', $crowd);
        }
        $this->display($this->template);
    }

    // 获取课程列表
    public function courseList() {

        $id = intval($_POST['id']);

        if (!$id) {
            echo 0; exit;
        }

        // 获取科目配置
        $subjects = C('COURSE_TYPE');

        $subject = M('ClassSubjectTeacher')->where(array('s_id' => $this->authInfo['s_id'], 'c_id' => $id))->getField('cst_course', TRUE);

        foreach ($subject as $key => $value) {
            $course[$key]['subject_name'] = $subjects[$value];
            $course[$key]['subject_id'] = $value;
        }

        echo json_encode($course);
    }

    // 获取作业列表
    public function lists() {

        $where['act_type'] = intval($_POST['act_type']);

        if ($this->authInfo['a_type'] == 1) {

            // 获取我所在的群组ID
            $croidArr = M('AuthCrowd')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->getField('cro_id', TRUE);

            if (!is_array($croidArr)) {
                $croidArr[0] = 0;
            }

            // 查询条件
            $where['s_id'] = $this->authInfo['s_id'];

            $where['_string'] = '1 != 1';

            if (implode(',', $this->authInfo['c_id'])) {
                $where['_string'] .= ' OR c_id IN('.implode(',', $this->authInfo['c_id']).')';
            }

            if (implode(',', $croidArr)) {
                $where['_string'] .= ' OR cro_id IN('.implode(',', $croidArr).')';
            }

            // 接收条件
            if (intval($_POST['ap_course'])) {
                $where['ap_course'] = intval($_POST['ap_course']);
            }

            if (intval($_POST['c_id'])) {
                $where['c_id'] = intval($_POST['c_id']);
            }

            if (intval($_POST['cro_id'])) {
                $where['cro_id'] = intval($_POST['cro_id']);
            }

            $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

            $ActivityPublish = getListByPage('ActivityPublish', 'ap_id DESC', $where, C('PAGE_SIZE'), 1, $p);

            // 获取创建活动的教师信息
            $teacher = getDataByArray('Auth', $ActivityPublish['list'], 'a_id', 'a_id, a_nickname');

            // 获取学科配置
            $subject = C('COURSE_TYPE');

            // 获取作业答案
            $ActivityData = M('ActivityData')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('ap_id, ad_id, ad_status')->select();

            $ActivityData = setArrayByField($ActivityData, 'ap_id');

            // 获取群组信息
            $crowd = M('Crowd')->where(array('cro_id' => array('IN', $croidArr)))->select();
            $crowd = setArrayByField($crowd, 'cro_id');

            // 获取班级信息
            $class = M('Class')->where(array('c_id' => array('IN', $this->authInfo['c_id'])))->select();
            $class = setArrayByField($class, 'c_id');

            // 转换班级名称
            foreach ($class as $ckey => $cvalue) {
                $c_grade = YearToGrade($cvalue['c_grade'], $this->authInfo['s_id']);
                $class[$ckey]['c_name'] = replaceClassTitle($this->authInfo['s_id'], $cvalue['c_type'], $c_grade, $cvalue['c_title'], $value['c_is_graduation']);
            }

            // 处理数据
            foreach ($ActivityPublish['list'] as $key => $value) {
                $ActivityPublish['list'][$key]['a_nickname'] = $teacher[$value['a_id']]['a_nickname'];
                $ActivityPublish['list'][$key]['subject_name'] = $subject[$value['ap_course']];
                $ActivityPublish['list'][$key]['ap_created'] = date('Y.m.d', $value['ap_created']);
                $ActivityPublish['list'][$key]['ap_complete_time'] = date('Y.m.d', $value['ap_complete_time']);

                if ($value['c_id']) {
                    $ActivityPublish['list'][$key]['c_name'] = $class[$value['c_id']]['c_name'];
                } else {
                    $ActivityPublish['list'][$key]['c_name'] = '';
                }

                if ($value['cro_id']) {
                    $ActivityPublish['list'][$key]['cro_name'] = $crowd[$value['cro_id']]['cro_title'];
                } else {
                    $ActivityPublish['list'][$key]['cro_name'] = '';
                }

                if ($ActivityData[$value['ap_id']]) {
                    $ActivityPublish['list'][$key]['ad_status'] = $ActivityData[$value['ap_id']]['ad_status'];
                    $ActivityPublish['list'][$key]['ad_id'] = $ActivityData[$value['ap_id']]['ad_id'];
                } else {
                    $ActivityPublish['list'][$key]['ad_status'] = 0;
                }
            }

        }
        if ($this->authInfo['a_type'] == 2) {

            // 查询条件
            $where['a_id'] = $this->authInfo['a_id'];
            $where['s_id'] = $this->authInfo['s_id'];

            $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

            // 接收条件
            // 班级ID
            if (intval($_POST['c_id'])) {
                $where['c_id'] = intval($_POST['c_id']);
            }

            // 群组ID
            if (intval($_POST['cro_id'])) {
                $where['cro_id'] = intval($_POST['cro_id']);
            }

            if (trim($_POST['order'])) {
                $order = trim($_POST['order']);
            }

            // 时间段
            $timeWithIn = C('ACTIVITYLIST_WITH_IN');

            $ap_created = intval($_POST['ap_created']);

            if ($ap_created) {
                $where['ap_created'] = array('gt', time() - $timeWithIn[$ap_created]['sql']);
            }

            // 获取已发布的作业数据
            $ActivityPublish = getListByPage('ActivityPublish', $order, $where, C('PAGE_SIZE'), 1, $p);

            // 获取班级和群组信息
            $classCrowdInfo = $this->getClassCrowdInfo('c_id', 'cro_id');

            $classInfo = $classCrowdInfo['class'];
            $crowdInfo = $classCrowdInfo['crowd'];

            // 处理数据 显示班级名称 活动标题 群组名称
            foreach ($ActivityPublish['list'] as $key => $value) {

                // 布置时间
                $ActivityPublish['list'][$key]['ap_created'] = date('Y-m-d', $value['ap_created']);

                // 班级名称
                if ($value['c_id']) {
                    $ActivityPublish['list'][$key]['c_name'] = $classInfo[$value['c_id']]['c_name'];
                }

                // 作业名称
                if ($value['cro_id']) {
                    $ActivityPublish['list'][$key]['cro_name'] = $crowdInfo[$value['cro_id']]['cro_title'];
                }
            }


        }

        echo json_encode($ActivityPublish);

    }

    // 教师批改作业
    public function correct() {

        // 接收参数
        $ap_id = intval($_GET['ap_id']);

        // 验证
        if (!$ap_id || $this->authInfo['a_type'] != 2) {
            $this->redirect('index');
        }

        // 验证教师权限
        $homework = M('ActivityPublish')->where(array('ap_id' => $ap_id, 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find();
        if (!$homework) {
            $this->redirect('index');
        }

        // 验证此活动是否为教师创建
        $activity = M('Activity')->where(array('act_id' => $homework['act_id'], 'a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->find();
        if (!$activity) {
            $this->redirect('index');
        }

        // 获取我所教的班级和群组信息
        $classCrowdInfo = $this->getClassCrowdInfo();

        // 获取发布的班级和群组id
        $cIdCroId = M('ActivityPublish')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id'], 'act_id' => $homework['act_id']))->field('act_id,c_id,cro_id')->select();

        // 在 $classCrowdInfo 中做减法
        $class = setArrayByField($classCrowdInfo['class'], 'c_id');
        $crowd = setArrayByField($classCrowdInfo['crowd'], 'cro_id');
        foreach ($cIdCroId as $key => &$value ) {
            if ($value['c_id'] && array_key_exists($value['c_id'], $class)) {
                $tmpClass[$value['c_id']] = $class[$value['c_id']];
            }
            if ($value['cro_id'] && array_key_exists($value['cro_id'], $crowd)) {
                $tmpCrowd[$value['cro_id']] = $crowd[$value['cro_id']];
            }
        }

        // 选项
        $tit = array('A', 'B', 'C', 'D', 'E', 'F');

        // 获取题目及正确答案列表
        $list = M('Topic')->where(array('to_id' => array('IN', $homework['to_id'])))->select();

        foreach ($list as $key => $value) {

            $list[$key]['to_title'] = strip_tags(htmlspecialchars_decode($value['to_title']));

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

            $list[$key]['to_option'] = explode(',', $value['to_option']);

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

                    $list[$key]['exact'] .= ',<span class="exact">' . $eValue . '</span>';

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
                        $picture_answer[$pk] = turnTpl($pv);
                    }

                    $list[$key]['picture_answer'] = $picture_answer;
                }
            }

        }

        if ($homework['c_id'] != 0) {
            $homework['field'] = 'c_id';
        }

        if ($homework['cro_id'] != 0) {
            $homework['field'] = 'cro_id';
        }

        $this->assign('tit', $tit);
        $this->assign('list', $list);
        $this->assign('homework', $homework);
        $this->assign('class', $tmpClass);
        $this->assign('crowd', $tmpCrowd);
        $this->display();

    }

    public function listStudent() {

        // 接收参数
        $ap_id = intval($_POST['ap_id']);

        if (!$ap_id) {
            $this->error('非法操作');
        }

        if (!$res = M('ActivityPublish')->where(array('ap_id' => $ap_id, 'a_id' => $this->authInfo['a_id']))->find()) {
            $this->error('非法操作');
        }

        $c_id = intval($_REQUEST['c_id']);
        $cro_id = intval($_REQUEST['cro_id']);

        // 获取班级里的学生
        if ($c_id) {
            $student = M('ClassStudent')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->select();
        }

        // 获取群组中的学生
        if ($cro_id) {
            $student = M('AuthCrowd')->where(array('cro_id' => $cro_id, 's_id' => $this->authInfo['s_id']))->select();
        }

        // 获取用户信息
        $student = getDataByArray('Auth', $student, 'a_id', 'a_id, a_nickname, a_type, a_sex');

        // 获取学生答案
        $data = M('ActivityData')->where(array('ap_id' => $ap_id, 'a_id' => array('IN', implode(',', getValueByField($student, 'a_id')))))->field('a_id, ad_id, ad_status')->select();


        foreach ($data as $key => $value) {
            $student[$value['a_id']]['ad_id'] = $value['ad_id'];
            $student[$value['a_id']]['ad_status'] = $value['ad_status'];
        }

        // 获取学生头像
        foreach ($student as $key => $value) {
            $student[$key]['a_avatar'] = getAuthAvatar($value['a_avatar'], $value['a_type'], $value['a_sex']);
        }

        sort($student);

        echo json_encode($student);
    }

    /* getClassCrowdInfo
     * 获取我所教的班级和群组信息
     * 根据班级ID重组数组 $c_field
     * 根据群组ID重组数组 $cro_field
     */
    public function getClassCrowdInfo($c_field = '', $cro_field = '') {

        // 获取我所教的班级ID
        $cidArr = $this->authInfo['subject_teacher_class'];

        // 班级数据
        $class = M('Class')->where(array('c_id' => array('IN', implode(',', $cidArr))))->field('c_id, c_title, c_grade, c_type, c_is_graduation')->select();

        if ($c_field) {
            $class = setArrayByField($class, $c_field);
        }

        // 转换班级名称
        foreach ($class as $key => $value) {
            $c_grade = YearToGrade($value['c_grade'], $this->authInfo['s_id']);
            $class[$key]['c_name'] = replaceClassTitle($this->authInfo['s_id'], $value['c_type'], $c_grade, $value['c_title'], $value['c_is_graduation']);
        }

        $result['class'] = $class;

        // 获取群组信息
        $crowd = M('Crowd')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('cro_id, cro_title')->select();

        if ($cro_field) {
            $crowd = setArrayByField($crowd, $cro_field);
        }

        $result['crowd'] = $crowd;

        return $result;
    }

    // 教师批改学生作业
    public function setStatus() {

        // 接收参数
        $ajax = intval($_POST['ajax']);

        // 判断教师身份
        if ($this->authInfo['a_type'] != 2) {
            $this->error();
        }

        // 教师批改学生作业
        $re = D('Homework')->teacherSetStatus($this->authInfo['a_id'], $this->authInfo['s_id']);

        if ($ajax) {
            if ($re) {
                $this->success();
            } else {
                $this->error();
            }
        }
    }

    // 下载学生作业文档
    public function downLoad() {

        // 接收参数
        $a_id = intval($_REQUEST['a_id']);
        $ap_id = intval($_REQUEST['ap_id']);
        $fileName = $_REQUEST['fileName'];

        if (!$a_id || !$ap_id || !$fileName) {
            echo 0;exit;
        }

        $filePath = C('ACTIVITY_PATH') . 'ActivityData/' . ($ap_id % C('MAX_FILES_NUM')) . '/' . $ap_id . '/' . $a_id . '/';

        $file = pathinfo($fileName);

        download($filePath, iconv('utf-8', 'gbk', $file['filename']), $file['extension']);

    }

}