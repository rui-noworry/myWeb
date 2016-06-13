<?php
/**
 * SpaceAction
 * 空间模块
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-2
 *
 */
class SpaceAction extends BaseAction{

    // 初始化
    public function _initialize() {
        parent::_initialize();
        $this->bannerOn = 3;
    }

    public function index() {

        /* 日历 */
        // 计算当前时间戳
        $ym = date("Y-m");

        $allday = date("t");

        // 读取当前月的时间范围
        $strat_time = strtotime($ym."-1");
        $end_time = strtotime($ym."-".$allday) + 3600 * 24;

        $map['a_id'] = $this->authInfo['a_id'];
        $map['rem_time'] = array(array('egt', $strat_time), array('lt', $end_time));

        $list = M('Remind')->where($map)->field('rem_time')->select();

        // 整理时间格式
        foreach($list as  $v) {

            $reTimeList[] = date("n_j_Y", $v['rem_time']);
        }

        // 获取配置课程
        $subjects = C('COURSE_TYPE');

        // 获取个人信息 所在学校
        $school = M('School')->where(array('s_id' => $this->authInfo['s_id']))->getField('s_title');

        // 若是学生身份
        if ($this->authInfo['a_type'] == 1) {

            // 我的老师列表
            $myTeacher = D('Teacher')->lists($this->authInfo['a_id'], $this->authInfo['s_id'], $this->authInfo['c_id']);

            $this->assign('myTeacher', $myTeacher);

            /* 课程动态 */

            // 获取所在年级
            $grade = YearToGrade($this->authInfo['a_year'], $this->authInfo['s_id']);

            // 获取我所在学校所在年级学的课程
            $where['s_id'] = $this->authInfo['s_id'];
            $where['gs_grade'] = $grade;
            $where['gs_type'] = $this->authInfo['a_school_type'];

            $subject_ids = M('GradeCourse')->where($where)->getField('gc_course', TRUE);

            // 课程
            foreach ($subject_ids as $v) {
                $course[] = array($subjects[$v], $v);
            }

            $this->assign('pages', ceil(count($course)/3));
            $this->assign('course', $course);

        }

        // 若是教师身份
        if ($this->authInfo['a_type'] == 2) {

            // 获取教师所教的班级
            $class = M('Class')->where(array('c_id' => array('IN', $this->authInfo['c_id'])))->field('c_id, c_title, c_grade, c_type, c_is_graduation')->select();

            // 任课教师获取与所任课程相关的学生动态
            $classSubjectTeacher = M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'c_id' => array('IN', $this->authInfo['c_id'])))->select();

            $classSubjectTeacher = setArrayByField($classSubjectTeacher, 'c_id');

            // 转换班级名称
            foreach ($class as $key => $value) {
                $c_grade = YearToGrade($value['c_grade'], $this->authInfo['s_id']);
                $class[$key]['c_replace_title'] = replaceClassTitle($this->authInfo['s_id'], $value['c_type'], $c_grade, $value['c_title'], $value['c_is_graduation']);

                // 任课教师且非班主任
                if ($class['a_id'] != $this->authInfo['a_id']) {
                    $class[$key]['subject_id'] = $classSubjectTeacher[$value['c_id']]['cst_course'];
                }
            }

            $this->assign('pages', ceil(count($class)/3));
            $this->assign('class', $class);
        }

        // 与我相关的动态
        $trendToMe = D('Trend')->lists($this->authInfo['a_id']);

        // 页面赋值
        $this->assign('reTimeList', $reTimeList);
        $this->assign('school', $school);
        $this->assign('trendToMe', $trendToMe['list']);
        $this->display();
    }

    // 添加提醒
    public function insertRemind() {

        $data['rem_time'] = strtotime($_POST['rTime']);

        $data['a_id'] = $this->authInfo['a_id'];

        $id = M('Remind')->where($data)->getField('rem_id');

        $data['rem_title'] = $_POST['rStr'];

        //判断是添加还是修改，删除
        if ($id) {
            $data['rem_id'] = $id;
            if(trim($data['rem_title']) == '') {
                $result['delete'] = M('Remind')->delete($id);
            }else {
                $res = M('Remind')->save($data);
            }

        }else {
            if(trim($_POST['rStr']) !== '') {
                $res = M('Remind')->add($data);
            }
        }

        // 返回状态
        if ($res) {
            $result['status'] = 1;
            $result['info'] = $res;
        }else {
            $result['status'] = 0;
            $result['info'] = "添加失败！";
        }

        echo json_encode($result);

    }
    // 读取当前月提醒时间
    public function readRemind() {

        // 计算当前月时间戳
        $year = intval($_POST['nowYear']);
        $month = intval($_POST['nowMonth']);
        // 当前月的天数
        $allday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $strat_time = strtotime($year."-".$month."-1");
        $end_time = strtotime($year."-".$month."-".$allday) + 3600 * 24;

        $map['a_id'] = $this->authInfo['a_id'];
        $map['rem_time'] = array(array('EGT', $strat_time), array('lt', $end_time));

        $list = M('Remind')->where($map)->field('rem_time')->select();

        // 整理时间格式
        foreach($list as  $v) {

            $reTimeList[] = date("n_j_Y", $v['rem_time']);
        }

        if ($reTimeList){
            $result['status'] = 1;
            $result['reTimeList'] = $reTimeList;
        }else {
            $result['status'] = 0;
        }

        echo json_encode($result);
    }

    // 获取提醒
    public function getRemind() {

        $remTime = strtotime($_POST['remTime']);
        $remTitle = M('Remind')->where(array('rem_time' => $remTime))->getField('rem_title');
        if ($remTitle){
            $result['status'] = 1;
            $result['remTitle'] = $remTitle;
        }else {
            $result['status'] = 0;
        }

        echo json_encode($result);
    }

}
?>