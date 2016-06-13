<?php
/**
 * ClassworkAction
 * 练习模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-27
 *
 */
class ClassworkAction extends BaseAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取我所在的学校
        $school = loadCache('school');
        $this->school = $school[$this->authInfo['s_id']]['s_name'];
        $this->template = strtolower(ACTION_NAME).$this->authInfo['a_type'];
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

            // 获取我所学的科目
            $subjects = C('COURSE_TYPE');

            $subject = M('ClassSubjectTeacher')->where(array('s_id' => $this->authInfo['s_id'], 'c_id' => array('IN', $cidArr)))->getField('cst_course', TRUE);

            foreach ($subject as $key => $value) {
                $course[$key]['subject_name'] = $subjects[$value];
                $course[$key]['subject_id'] = $value;
            }


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


    // 教师批改练习
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

        $class = $classCrowdInfo['class'];
        $crowd = $classCrowdInfo['crowd'];

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
        $this->assign('class', $class);
        $this->assign('crowd', $crowd);
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
            $student = M('AuthCrowd')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->select();
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

    // 教师批改学生练习
    public function setStatus() {

        // 接收参数
        $ajax = intval($_POST['ajax']);

        // 判断教师身份
        if ($this->authInfo['a_type'] != 2) {
            $this->error();
        }

        // 教师批改学生练习
        $re = D('Classwork')->teacherSetStatus($this->authInfo['a_id'], $this->authInfo['s_id']);

        if ($ajax) {
            if ($re) {
                $this->success();
            } else {
                $this->error();
            }
        }
    }

    // 下载学生文档
    public function downLoad() {

        // 接收参数
        $a_id = intval($_REQUEST['a_id']);
        $ap_id = intval($_REQUEST['ap_id']);
        $fileName = $_REQUEST['fileName'];

        if (!$a_id || !$ap_id || !$fileName) {
            echo 0;exit;
        }

        $act_id = M('ActivityPublish')->where(array('ap_id' => $ap_id))->getField('act_id');
        $filePath = C('ACTIVITY_PATH') . 'ActivityData/' . ($act_id % C('MAX_FILES_NUM')) . '/' . $act_id . '/' . $a_id . '/';


        $file = pathinfo($fileName);

        download($filePath, $file['filename'], $file['extension']);

    }

}