<?php
/**
 * AuthModel
 * 用户模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-7
 *
 */
class AuthModel extends CommonModel{

    protected $_validate = array(
        array('a_account', 'require', '帐号必须'),
        array('a_password', 'require', '密码必须'),
        array('a_account', '', '帐号已经存在', 0, 'unique', self::MODEL_INSERT),
    );

    protected $_auto = array(
        array('a_created', 'time', self::MODEL_INSERT, 'function'),
        array('a_updated', 'time', self::MODEL_UPDATE, 'function'),
        array('a_password', 'md5', self::MODEL_INSERT, 'function'),
    );

    public function _after_insert() {

        // 接收参数
        // 老师授课
        $map['teacher'] = explode(',', $_POST['teacher']);
        $map['s_id'] = $class['s_id'] = intval($_POST['s_id']);

        // 学生班级
        $class['c_id'] = $_POST['c_id'];
        $class['c_type'] = $_POST['a_school_type'];
        $class['c_grade'] = GradeToYear($_POST['a_grade'], $class['s_id']);

        // 用户类型
        $type = $_POST['a_type'];

        unset($_POST);

        $_POST['a_id'] = mysql_insert_id();

        if($type == 1) {

            $this->addStudent($_POST['a_id'], $class);
        }

        if ($type == 2) {

            M("Teacher")->where(array('a_id' => $_POST['a_id'], 's_id' => $map['s_id']))->delete();

            $_POST['s_id'] = $map['s_id'];

            foreach ($map['teacher'] as $value) {

                $_POST['t_subject'] = $value;

                $model = M('Teacher');

                if (false === $model->create()) {
                    $this->error($model->getError());
                }

                $model->add();
            }

            // 添加默认导航
            $navigations = C('NAVIGATION');

            foreach ($navigations as $key => $value) {

                $nav['na_title'] = $value['title'];
                $nav['na_url'] = $value['url'];
                $nav['na_sort'] = $key;
                $nav['a_id'] = $_POST['a_id'];
                $nav['na_created'] = time();

                M('Navigation')->add($nav);
            }
        }
    }

    // 更新
    public function _after_update() {

        // 接收参数
        $map['a_id'] = $_POST['a_id'];

        // 老师授课
        $map['teacher'] = explode(',', $_POST['teacher']);
        $map['s_id'] = $class['s_id'] = intval($_POST['s_id']);

        // 学生班级
        $class['c_id'] = $_POST['c_id'];
        $class['c_type'] = $_POST['a_school_type'];
        $class['c_grade'] = GradeToYear($_POST['a_grade'], $class['s_id']);

        // 用户类型
        $type = $_POST['a_type'];

        unset($_POST);

        $_POST['a_id'] = $map['a_id'];

        if ($type == 2) {

            M("Teacher")->where(array('a_id' => $_POST['a_id'], 's_id' => $map['s_id']))->delete();

            $_POST['s_id'] = $map['s_id'];

            foreach ($map['teacher'] as $value) {
                $_POST['t_subject'] = $value;

                $model = M('Teacher');

                if (false === $model->create()) {
                    $this->error($model->getError());
                }

                $model->add();
            }
        }
    }

    // 增加一个学生
    // $aid 学生ID  $class 班级数据
    public function addStudent($aid, $class, $oldCid = 0) {

        unset($_POST);

        if (intval($_POST['curCid'])) {
            $oldCid = intval($_POST['curCid']);
        }

        // 如果转班级
        if ($oldCid) {

            // 删除班级关系
            M('ClassStudent')->where(array('c_id' => $oldCid, 'a_id' => $aid, 's_id' => $class['s_id']))->delete();

            // 原班级人数减少
            M('Class')->where(array('c_id' => $oldCid, 's_id' => $class['s_id']))->setDec('c_peoples');
        }

        // 现班级人数增加
        M('Class')->where(array('c_id' => $class['c_id'], 's_id' => $class['s_id']))->setInc('c_peoples');

        $_POST['a_id'] = $aid;
        $_POST['c_id'] = $class['c_id'];
        $_POST['s_id'] = $class['s_id'];

        // 增加新的班级关系
        M("ClassStudent")->add($_POST);
        // 条件
        $where['a_id'] = $aid;
        $s_id = $where['s_id'] = $_POST['s_id'] = $class['s_id'];
        $where['as_end_time'] = 0;
        $where['c_id'] = $oldCid;

        // 查询学生在校记录
        $authSchool = M("AuthSchool")->where($where)->field('as_id,c_id,as_type,as_start_time,as_start_grade')->find();

        if ($authSchool) {

            // 学生班级变化
            if ($authSchool['c_id'] != $class['c_id']) {

                unset($_POST);

                // 更新原记录
                $as_end_grade = GradeToYear(intval($authSchool['as_start_grade']) + intval(date('Y', time())) - intval(date('Y', $authSchool['as_start_time'])), $s_id);

                if ($class['c_type'] != $authSchool['as_type']) {

                    if ($class['c_type'] < $authSchool['as_type']) {
                        // 开除
                        $as_status = 5;
                    }

                    if ($class['c_type'] > $authSchool['as_type']) {
                        // 毕业
                        $as_status = 1;
                    }

                } else {
                    // 调班
                    if ($class['c_grade'] == $as_end_grade) {
                        $as_status = 2;
                    }

                    // 留级
                    if ($class['c_grade'] < $as_end_grade) {
                        $as_status = 3;
                    }

                    // 跳级
                    if ($class['c_grade'] > $as_end_grade) {
                        $as_status = 4;
                    }
                }

                unset($_POST);

                $_POST['as_id'] = $authSchool['as_id'];
                $_POST['as_end_time'] = time();
                $_POST['as_status'] = $as_status;

                M("AuthSchool")->save($_POST);
            }
        }

        // 增加新记录
        unset($_POST);

        $_POST['a_id'] = $aid;
        $_POST['s_id'] = $s_id;
        $_POST['c_id'] = $class['c_id'];
        $_POST['as_type'] = $class['c_type'];
        $_POST['as_start_grade'] = YearToGrade($class['c_grade'], $s_id);
        $_POST['as_start_time'] = time();

        M("AuthSchool")->add($_POST);
    }

    // 教师列表
    public function lists($s_id) {

        if (!$s_id) {
            $this->error('参数错误');
        }

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        $map['s_id'] = $s_id;
        $map['a_type'] = 2;
        $order = trim($_POST['order']);

        if ($_POST['a_nickname']) {
            $map['a_nickname'] = array('LIKE', '%'.trim($_POST['a_nickname']).'%');
        }

        $result = getListByPage('Auth', $order, $map, C('PAGE_SIZE'), 1, $p);

        // 获取教师授课课程
        $res = M("Teacher")->where(array('s_id' => $map['s_id']))->field('t_subject, a_id')->select();

        foreach ($res as $key => $value) {
            $course[$value['a_id']][] = getTypeNameById($value['t_subject'], 'COURSE_TYPE');
        }

        // 重新组织数据
        $result['status'] = 0;
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['a_last_login_ip'] = long2ip($value['a_last_login_ip']);
            $result['list'][$key]['t_subject'] = str_replace('null', '', implode(',', $course[$value['a_id']]));

        }

        if ($result['list']) {
            $result['status'] = 1;
        }

        return $result;
    }

    // 学生列表
    public function studentList($s_id, $type = 0) {

        if (!$s_id) {
            $this->error('参数错误');
        }

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        $map['s_id'] = $s_id;
        $map['a_type'] = 1;
        $order = trim($_POST['order']);

        if ($_POST['a_nickname']) {
            $map['a_nickname'] = array('LIKE', '%'.$_POST['a_nickname'].'%');
        }

        if (intval($_POST['a_year'])) {
            $map['a_year'] = intval($_POST['a_year']);
        }

        // 获取数据
        if ($type) {
            $result['list'] = M('Auth')->where($map)->select();
        } else {
            $result = getListByPage('Auth', $order, $map, C('PAGE_SIZE'), 1, $p);
        }

        // 获取学生所在班级
        $auth = setArrayByField($result['list'], 'a_id');

        $res = M('ClassStudent')->where(array('a_id' => array('IN', array_keys($auth))))->select();

        $class = getDataByArray('Class', $res, 'c_id', 'c_title, c_id, c_type, s_id, c_grade, c_is_graduation,ma_id');

        foreach ($res as $key => $value) {

            $c_grade = YearToGrade($class[$value['c_id']]['c_grade'], $class[$value['c_id']]['s_id']);

            $classArr[$value['a_id']][] = replaceClassTitle($class[$value['c_id']]['s_id'], $class[$value['c_id']]['c_type'], $c_grade, $class[$value['c_id']]['c_title'], 0, $class[$value['c_id']]['ma_id']);
        }

        // 重新组织数据
        foreach ($auth as $key => $value) {
            $auth[$key]['c_name'] = trim(implode(',', $classArr[$key]), ',');
            $auth[$key]['a_last_login_ip'] = long2ip($value['a_last_login_ip']);
        }

        foreach ($auth as $key => $value) {
            $results['list'][] = $value;
        }

        $results['page'] = $result['page'];

        $results['status'] = 0;
        if ($results['list']) {
            $results['status'] = 1;
        }

        return $results;

    }


}

?>