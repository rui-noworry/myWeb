<?php

/**
 * CourseAction
 * 课程类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-6
 *
 */
class CourseAction extends BaseAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();
        if (!$this->authInfo['s_id']) {
            $this->redirect('/Index');
        }
        $this->template = strtolower(ACTION_NAME).$this->authInfo['a_type'];
    }

    // 默认进入展示课程目录
    public function index() {

        if ($this->authInfo['a_type'] == 1) {

            // 获取我所在的班级
            $cidArr = $this->authInfo['c_id'];

            // 获取我在本学年所学课程
            $xq = getXq($this->authInfo['s_id']);

            $where['c_id'] = array('IN', implode(',', $cidArr));
            $where['cst_year'] = $xq['cc_year'];
            $where['cst_xq'] = $xq['cc_xq'];

            // 获取标准课程
            $result = M('ClassSubjectTeacher')->where($where)->select();

            foreach ($result as $ras) {
                if ($ras['co_id']) {
                    $coIds[] = $ras['co_id'];
                }
            }

            $coIds = implode(',', $coIds);

            $map = '';
            if ($coIds) {
                $map = 'co_id IN (' . $coIds . ') OR ';
            }

            $map .= '(';
            if ($cidArr) {
                foreach ($cidArr as $value) {
                    $map .= " c_id like '%,".$value.",%' OR ";
                }
            }

            $map = substr($map, 0, -3);

            // 获取所在群组
            $croidArr = M('AuthCrowd')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->getField('cro_id', TRUE);

            // 组织查询条件
            if ($croidArr) {
                foreach ($croidArr as $value) {
                    $map .= " OR cro_id like '%,".$value.",%'";
                }
            }

            $map .= ')';

            // 获取课程信息
            $course = M('Course')->where($map)->select();
            $course = setArrayByField($course, 'co_id');

            // 获取班级信息
            $class = M('Class')->where(array('c_id' => array('IN', strval(implode(',', $cidArr)))))->select();
            $class = setArrayByField($class, 'c_id');

            // 获取群组信息
            $crowd = M('Crowd')->where(array('cro_id' => array('IN', strval(implode(',', $croidArr)))))->select();
            $crowd = setArrayByField($crowd, 'cro_id');

            // 获取教师信息
            $auth = getDataByArray('Auth', $course, 'a_id');

            // 获取学科
            $subjects = C('COURSE_TYPE');

            // 处理数据
            foreach ($result as $key => $value) {

                // 学科
                if ($value['cst_course']) {
                    $result[$key]['subject'] = $subjects[$value['cst_course']];
                }

                // 已指定教师
                if ($value['a_id']) {
                    $result[$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
                }

                // 已指定教案
                if ($value['co_id']) {
                    $result[$key]['co_title'] = $course[$value['co_id']]['co_title'];
                    $result[$key]['co_cover'] = getCourseCover($course[$value['co_id']]['co_cover'], $course[$value['co_id']]['co_subject']);
                } else {
                    $result[$key]['co_cover'] = getCourseCover('', $value['cst_course']);
                }

                $result[$key]['c_name'] = replaceClassTitle($class[$value['c_id']]['s_id'], $class[$value['c_id']]['c_type'], YearToGrade($class[$value['c_id']]['c_grade'], $class[$value['c_id']]['s_id']), $class[$value['c_id']]['c_title'], $class[$value['c_id']]['c_is_graduation'], $class[$value['c_id']]['ma_id']);
            }

            $tmpArray = array();

            // 非标准课程
            foreach ($course as $cKey => $cValue) {

                $tmp = array();
                $tmp = $cValue;

                $tmp['co_cover'] = getCourseCover($cValue['co_cover'], $cValue['co_subject']);
                $tmp['a_nickname'] = $auth[$cValue['a_id']]['a_nickname'];
                $tmp['subject'] = $subjects[$cValue['co_subject']];

                if ($cValue['co_version'] == 0) {

                    if ($cValue['c_id'] != ',') {
                        $tmpCid = explode(',', substr($cValue['c_id'], 1, -1));
                        foreach ($tmpCid as $tcValue) {
                            if (in_array($tcValue, $cidArr)) {
                                $tmp['c_id'] = $tcValue;
                                $tmp['cro_id'] = 0;
                                $tmp['cro_name'] = '';
                                $tmp['c_name'] = replaceClassTitle($class[$tcValue]['s_id'], $class[$tcValue]['c_type'], YearToGrade($class[$tcValue]['c_grade'], $class[$tcValue]['s_id']), $class[$tcValue]['c_title'], $class[$tcValue]['c_is_graduation'], $class[$tcValue]['ma_id']);
                                $tmpArray[] = $tmp;
                            }
                        }
                    }

                    if ($cValue['cro_id'] != ',') {
                        $tmpCroId = explode(',', substr($cValue['cro_id'], 1, -1));
                        foreach ($tmpCroId as $tcoValue) {
                            if (in_array($tcoValue, $croidArr)) {
                                $tmp['c_id'] = 0;
                                $tmp['cro_id'] = $tcoValue;
                                $tmp['c_name'] = '';
                                $tmp['cro_name'] = $crowd[$tcoValue]['cro_title'];
                                $tmpArray[] = $tmp;
                            }
                        }
                    }
                } else {
                    if ($cValue['cro_id'] != ',') {
                        $tmpCroId = explode(',', substr($cValue['cro_id'], 1, -1));

                        foreach ($tmpCroId as $tcoValue) {
                            if (in_array($tcoValue, $croidArr)) {
                                $tmp['c_id'] = 0;
                                $tmp['cro_id'] = $tcoValue;
                                $tmp['c_name'] = '';
                                $tmp['cro_name'] = $crowd[$tcoValue]['cro_title'];
                                $tmpArray[] = $tmp;
                            }
                        }
                    }
                }
            }

            $result = array_merge($result, $tmpArray);
            $this->assign('list', $result);

        }

        if ($this->authInfo['a_type'] == 2) {

            // 获取教师授课列表
            $course = getListByPage('Course', 'co_id DESC', array('a_id' => $this->authInfo['a_id']), 50);

            // 获取课程指定的班级
            $course['list'] = $this->getBindClass($course['list']);

            // 获取该教师已被被指定的授课班级
            $class = $this->getAboutCourseInfo(0);

            // 获取该教师建的群组
            $group = M('Crowd')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('cro_id, cro_title')->select();

            $this->assign('bindClass', $bindClass);
            $this->assign('group', $group);
            $this->assign('class', $class);
            $this->assign('page', $course['page']);
            $this->assign('list', $course['list']);

        }
        $this->display($this->template);
    }

    // 添加课程
    public function add() {

        // 只有教师拥有创建课程的权限
        if ($this->authInfo['a_type'] != 2) {
            $this->redirect('index');
        }

        // 赋值
        $this->data = $this->getAboutCourseInfo();

        $this->display();
    }

    // 处理添加课程的逻辑
    public function insert() {

        // 权限验证
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 是否有上传
        if ($_FILES['co_cover']['size'] > 0) {
            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['co_cover'] = $this->upload($allowType['image'], C('COURSE_COVER_PATH'), TRUE, '210', '140', '210/');
        }

        // 创建者
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];

        // 校检、写入数据
        $result = $this->insertData();

        if (!$result) {
            $this->error('操作失败');
        }

        // 如果是自定义标签，则向课程标签关系表里插入数据
        if (intval($_POST['flag']) == 1) {
            $te_id = explode(',', trim($_POST['termId'], ','));
            foreach ($te_id as $key => $value) {
                $_POST['te_id'] = $value;
                $_POST['object_id'] = $result;
                $_POST['object_type'] = 1;
                $TermRelation = new TermRelationAction();
                $TermRelation->insert();
            }
        }

        // 值为1，开始备课；值为2，休息一会
        if ($_POST['co_flag'] == 1) {
            // 把刚刚添加的课程id传过去
            $this->redirect('/Lesson/index/course/' . $result);
        } else {
            // 返回课程列表
            $this->redirect('/Course');
        }
    }

    // 编辑课程
    public function edit() {

        // 权限检测
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 接收课程id
        $co_id = intval($_GET['id']);
        $course = M('Course')->where(array('co_id' => $co_id))->find();
        if (!$course) {
            $this->error('非法操作');
        }
        $this->assign('course', $course);

        // 如果是标准课程的话，需要得到相关的信息，标准课程的学段相关信息不能为0
        if ($course['co_type'] != 0) {
            $choosedTag = $this->chosedTag($course);
        }
        $this->choosedTag = $choosedTag ? $choosedTag : array();

        $this->data = $this->getAboutCourseInfo();

        // 获取选中的标签
        $teId = M('TermRelation')->where(array('object_id' => $co_id, 'object_tpe' => 1))->field('te_id')->select();
        if ($teId) {
            $teId = getValueByField($teId, 'te_id');
            $term = M('Term')->where(array('te_id' => array('in', $teId)))->order('te_count DESC')->select();
        }

        // 存放选中的标签
        $this->term = $term ? $term : array();

        // 存放选中标签的id，以便再更新时比较下，如果是同样的便不更新，有新的就更新
        $this->preStr = $teId ? implode(',', $teId) : '';

        $this->display();
    }

    // 处理已选中的标准课程数据
    public function chosedTag($data) {

        $arr = array();

        // 学段
        $arr[] = getTypeNameById($data['co_type'], 'SCHOOL_TYPE');

        // 专业
        if ($data['ma_id']) {
            $arr[] = M('Major')->where(array('ma_id' => $data['ma_id']))->getField('ma_title');
        }

        // 年级
        $arr[] = getGradeByType($data['co_type'], $this->authInfo['s_id'], $data['co_grade']);

        // 学期
        $arr[] = getTypeNameById($data['co_semester'], 'SEMESTER_TYPE');

        // 学科
        $arr[] = getTypeNameById($data['co_subject'], 'COURSE_TYPE');

        // 版本
        $arr[] = getTypeNameById($data['co_version'], 'VERSION_TYPE');

        return $arr;
    }

    // 获取与课程相关信息
    public function getAboutCourseInfo($flag = TRUE) {

        // 如果有该教师的授课的班级
        if ($this->authInfo['subject_teacher_class']) {

            // 获取班级课程教师表中该教师被指定的课程
            $cst = M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->select();

            // 获取相关的班级
            $class = getDataByArray('Class', $cst, 'c_id', 'c_id,s_id,c_type,c_grade,c_title,ma_id');

            // 处理课程相关数据
            foreach ($cst as $key => $value) {
                if (!in_array($value['c_id'], $tmp)) {
                    $tmp[] = $value['c_id'];
                    $value['c_title'] = replaceClassTitle($this->authInfo['s_id'], $class[$value['c_id']]['c_type'], $class[$value['c_id']]['c_grade'], $class[$value['c_id']]['c_title'], $value['c_is_graduation'], $class[$value['c_id']]['ma_id']);
                    $value['c_type'] = $class[$value['c_id']]['c_type'];
                    $value['c_grade'] = YearToGrade($class[$value['c_id']]['c_grade'], $value['s_id']);
                    $value['c_name'] = getTypeNameById($value['cst_course'], 'COURSE_TYPE');
                    $value['ma_id'] = $class[$value['c_id']]['ma_id'];
                    $data['cst'][$key] = $value;
                }
            }
        }

        // 如果有flag变量便查热门标签，以此来区分课程首页展示(不需要该查询)以及添加、编辑课程
        if ($flag) {

            // 取出标签库中最热门的45条
            $tag = M('Term')->order('te_count DESC')->limit(45)->select();
            $tag = array_chunk($tag, 15, TRUE);
            $data['tag'] = $tag;
        }

        // 学校学段类型
        $school = loadCache('school');
        $school = explode(',', $school[$this->authInfo['s_id']]['s_type']);
        $data['school'] = $school;

        // 获取教师可教授的学科
        $data['subject'] = M('Teacher')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->getField('t_subject', TRUE);

        return $data;
    }

    // 删除课程
    public function delete() {

        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 接收课程id
        $id = intval($_POST['co_id']);

        if ($id) {

            $lesson = M('Lesson')->where(array('co_id' => $id))->find();
            if ($lesson) {
                $this->error('请先清空该课程下的课文');
            }

            // 删除课程
            $flag = M('Course')->where(array('co_id' => $id, 'a_id' => $this->authInfo['a_id']))->delete();

            if ($flag) {

                // 删除数据成功后，再删除封面，如果是默认封面的话，就不删除
                basename(trim($_POST['co_cover'])) == '' ? '' : @unlink('.' . getCourseCover($_POST['co_cover']));

                // 删除课文时，也把班级教师授课表的co_id重新置为0
                M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => $id))->save(array('co_id' => 0));

                // 同时也得删除与之相对应的标签关系映射中的id，并把查询出来的term_id的count减1
                $teId = getValueByField(M('TermRelation')->where(array('object_id' => $id))->select(), 'te_id');
                M('TermRelation')->where(array('object_id' => $id))->delete();
                M('Term')->where(array('te_id' => array('in', $teId)))->save(array('te_count' => array('exp', 'te_count - 1')));

                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }
        }
    }

    // 更新课程
    public function update() {

        // 验证身份
        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 是否有上传，有上传就更新原有的封面，否则就删除以前的
        if ($_FILES['co_cover']['size'] > 0) {

            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['co_cover'] = $this->upload($allowType['image'], C('COURSE_COVER_PATH'), TRUE, '210', '140', '210/');
            // 删掉原有的封面
            unlink('.' . getCourseCover($_POST['co_cover_bak']));
        }

        // 教师id
        $_POST['a_id'] = $this->authInfo['a_id'];

        // 更新时间
        $_POST['co_updated'] = time();

        // 更新数据
        $result = $this->updateData();
        if (!$result) {
            $this->error('更新失败');
        }

        // 如果是以自定义课程更新的
        if (intval($_POST['flag']) == 1) {

            // 上次添加的标签
            $preStr = explode(',', trim($_POST['preStr'], ','));

            // 更新后的标签
            $nowStr = explode(',', trim($_POST['termId'], ','));

            // 比较下两个数组，不同的便是新增的，就循环插入term_relation表，其他的则从该表中删除
            $insertArr = array_diff($nowStr, $preStr);
            $deleteArr = array_diff($preStr, $nowStr);

            // 添加
            if ($insertArr) {
                foreach ($insertArr as $key => $value) {
                    $_POST['te_id'] = $value;
                    $_POST['object_id'] = intval($_POST['co_id']);
                    $_POST['object_type'] = 1;
                    $TermRelation = new TermRelationAction();
                    $TermRelation->insert();
                }
            }

            // 删除
            if ($deleteArr) {
                foreach ($deleteArr as $key => $value) {
                    $where['te_id'] = $value;
                    $where['object_id'] = $_POST['co_id'];
                    $where['object_type'] = 1;
                    M('TermRelation')->where($where)->delete();
                }
            }
        }

        // 值为1，开始备课；值为2，休息一会
        if (intval($_POST['co_flag']) == 1) {
            // 把刚刚添加的课程id传过去
            $this->redirect('/Lesson/index/course/' . intval($_POST['co_id']));
        } else {
            // 返回课程列表
            $this->redirect('/Course');
        }
    }

    // 接收课程首页中，教师把课程指定的班级和群组数据，并进行相关的验证
    public function sync() {

        // 验证
        $data['a_id'] = $this->authInfo['a_id'];
        $data['co_id'] = intval($_POST['co_id']);
        $course = $this->checkOwner($data, 'Course');

        // 过滤自定义课程
        if ($course['co_subject'] != 0) {

            // 两个教案不能绑定同一个班级
            if (strval($_POST['c_id'])) {
                $postCid = explode(',', strval($_POST['c_id']));
                $bindCid = array_filter(M('Course')->where(array('a_id' => $data['a_id'], 'co_id' => array('neq', $data['co_id']), 'co_version' => array('neq', 0)))->getField('c_id', TRUE));
                if ($bindCid) {
                    foreach ($bindCid as $key => $value) {
                        foreach ($postCid as $k => $v) {
                            if (strpos($value, ',' . $v . ',') !== FALSE) {
                                $this->error('对不起，多个教案不能同时指定一个班');
                            }
                        }
                    }
                }
            }

            // 两个教案不能绑定同一个班级
            if (strval($_POST['cro_id'])) {
                $postCroid = explode(',', strval($_POST['cro_id']));
                $bindCroid = array_filter(M('Course')->where(array('a_id' => $data['a_id'], 'co_id' => array('neq', $data['co_id']), 'co_version' => array('neq', 0)))->getField('cro_id', TRUE));
                if ($bindCroid) {
                    foreach ($bindCroid as $key => $value) {
                        foreach ($postCroid as $k => $v) {
                            if (strpos($value, ',' . $v . ',') !== FALSE) {
                                $this->error('对不起，多个教案不能同时指定一个群组');
                            }
                        }
                    }
                }
            }

            // 判断课程是否和班级对应
            if ($_POST['c_id']) {

                M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => $data['co_id'], 'cst_course' => $course['co_subject']))->save(array('co_id' => 0));

                // 同时在循环找不是该教师所教班级时，把是该教师异步提交上来的班级课程ID给指定
                $cst = M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'cst_course' => $course['co_subject'], 'c_id' => array('IN', strval($_POST['c_id']))))->select();

                $cId = explode(',', strval($_POST['c_id']));
                if (count($cst) != count($cId)) {
                    $this->error('学科不匹配');
                }

                foreach ($cst as $value) {
                    if (in_array($value['c_id'], $cId)) {
                        $data['cst_id'] =$value['cst_id'];
                        M('ClassSubjectTeacher')->where(array('a_id' => $this->authInfo['a_id'], 'c_id' => $value['c_id']))->save(array('co_id' => $data['co_id']));

                        // 更新教师授课历史表
                        M('TeacherCourseLog')->where(array('a_id' => $this->authInfo['a_id'], 'c_id' => $value['c_id'], 'tc_end_time' => array('eq',0), 'tc_subject' => $value['cst_course']))->save(array('co_id' => $data['co_id']));
                    }
                }

                // 动态表
                addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $_POST['c_id'], 3, 5, 0, $course['co_subject'], $course['co_title'], $course['co_id']);
            } else {

                // 同时如果传上来的c_id为空的话，则把该教师所对应的课程ID都置为空
                M('ClassSubjectTeacher')->where($data)->save(array('co_id' => 0));
            }

        }

        // 添加数据
        $save['c_id'] = $_POST['c_id'] ? (',' . $_POST['c_id'] . ',') : '';
        $save['cro_id'] = $_POST['cro_id'] ? (',' . $_POST['cro_id'] . ',') : '';
        $save['co_is_bind'] = 1;
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save($save);

        $this->success('课程设置成功');
    }

    // 在首页上单击年级时，异步显示班级
    public function searchClass() {

        // 依据传过来的年级和学校，转化为学年
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['c_grade'] = GradeToYear($_POST['c_grade'], $_POST['s_id']);

        $class = M('Class')->where($_POST)->field('c_id,c_grade,c_type,s_id,c_title,c_is_graduation,ma_id')->select();

        // 转化班级名称
        foreach ($class as $key => $value) {

            $class[$key]['c_name'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation'], $value['ma_id']);
        }

        echo json_encode($class);
    }

    // 在首页上单击年级下的班级时，显示班级下的成员
    public function searchMember() {

        // 查询班级下的学生id
        $_POST['s_id'] = $this->authInfo['s_id'];
        $students = M('ClassStudent')->where($_POST)->getField('a_id', TRUE);

        // 依据学生id查询学生姓名
        $studentArr = M('Auth')->where(array('a_id' => array('in', $students)))->field('a_nickname,a_id')->select();

        echo json_encode($studentArr);
    }

    // 向群组和群组用户表里添加数据
    public function insertCrowd() {

        // 向群组表里添加数据
        $data['cro_title'] = $_POST['cro_title'];
        $arr = explode(',', $_POST['a_id']);
        $data['cro_peoples'] = count($arr);
        $data['cro_created'] = time();
        $data['a_id'] = $this->authInfo['a_id'];
        $data['s_id'] = $this->authInfo['s_id'];
        $data['cro_id'] = M('Crowd')->add($data);

        // 向群组用户表添加数据
        foreach ($arr as $key => $value) {
            $data['a_id'] = $value;
            M('AuthCrowd')->add($data);
        }

        if ($data['cro_id']) {
            $this->success($data['cro_id']);
        }
        $this->error('添加群组失败!');
    }

    // 获取绑定班级和群组
    public function getBindClass($data) {

        // 循环数组，把班级ID群组ID给抽出来，放入一个数组中
        foreach ($data as $key => $value) {
            if ($value['c_id']) {
                $arr[] = explode(',', trim($value['c_id'], ','));
            }
            if ($value['cro_id']) {
                $cro[] = explode(',', trim($value['cro_id'], ','));
            }
        }

        // 引用TacheAction的二维数组转化为一维的方法，并查询班级表群组表，得到相关的字段
        $tache = new TacheAction;
        $arr = $tache->twoToOne($arr);
        $cro = $tache->twoToOne($cro);
        $bindClass = setArrayByField(M('Class')->where(array('c_id' => array('IN', $arr)))->field('s_id,c_type,c_grade,c_title,c_is_graduation,c_id,ma_id')->select(), 'c_id');

        // 得到班级名称
        foreach ($bindClass as $key => &$value) {
            $value['c_title'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation'], $value['ma_id']);
        }

        // 得到群组的名称
        $bindGroup = setArrayByField(M('Crowd')->where(array('cro_id' => array('IN', $cro)))->field('cro_id, cro_title')->select(), 'cro_id');

        // 把班级名称群组名称分别绑定到c_id、cro_id字段上
        foreach ($data as $key => &$value) {

            if ($value['c_id']) {
                $arr = explode(',', $value['c_id']);
                $tmp = '';
                foreach ($arr as $k => $v) {
                    if (array_key_exists($v, $bindClass)) {
                        $tmp .= $bindClass[$v]['c_title'] . ',';
                    }
                }
                $value['c_name'] = trim($tmp, ',');
            }

            if ($value['cro_id']) {
                $arr = explode(',', $value['cro_id']);
                $tmp = '';
                foreach ($arr as $k => $v) {
                    if (array_key_exists($v, $bindGroup)) {
                        $tmp .= $bindGroup[$v]['cro_title'] . ',';
                    }
                }
                $value['cro_name'] = trim($tmp, ',');
            }
        }

        return $data;
    }

    // 导出课程
    public function export() {

        Header("Content-type:text/html;charset=utf8");
        $type = intval($_REQUEST['type']);
        $co_id = intval($_REQUEST['co_id']);

        // 接收课程ID和导出类型
        if (!$co_id || !$type) {
            $this->error('非法操作');
        }

        // 权限检测
        $where['a_id'] = $this->authInfo['a_id'];
        $where['co_id'] = $co_id;
        $course = $this->checkOwner($where, 'Course');

        // type值为1导出xml格式
        if ($type == 1) {

            $downLoadName = iconv('utf-8', 'gbk', $course['co_title']);
            $downLoadPath = C('EXPORT_XML_PATH');
            $ext = 'xml';

            // 在导出xml前先比较下co_xml_time和co_updated，前者大于后者，就直接复制
            if ($course['co_xml_time'] <= $course['co_updated'] || !file_exists($downLoadPath . $downLoadName . '.' . $ext)) {

                // 查询课程下的课文
                $lesson = M('Lesson')->where($where)->select();

                // 查询课程下的课时
                $classhour = M('Classhour')->where(array('cl_status' => 1, 'l_id' => array('IN', implode(',', getValueByField($lesson, 'l_id')))))->select();

                // 查询课时下的环节
                $tache = M('Tache')->where(array('cl_id' => array('IN', implode(',', getValueByField($classhour, 'cl_id')))))->select();

                // 查询环节下的活动
                $activity = M('Activity')->where(array('ta_id' => array('IN', implode(',', getValueByField($tache, 'ta_id')))))->select();

                // 查询活动下的题目
                $topic = M('Topic')->where(array('to_id' => array('IN', implode(',', getValueByField($activity, 'act_rel')))))->select();


                // 重组数组
                foreach ($topic as $key => $value) {
                    $value['to_title'] = turnHtmlTag(stripslashes($value['to_title']));
                    $tmpTopic[$value['to_id']] = $value;
                }
                $topic = $tmpTopic;

                foreach ($lesson as $lKey => $lValue) {
                    $course['lesson'][$lValue['l_id']] = $lValue;
                }

                foreach ($classhour as $clKey => $clValue) {
                    $course['lesson'][$clValue['l_id']]['classhour'][$clValue['cl_id']] = $clValue;
                }

                foreach ($tache as $taKey => $taValue) {
                    $course['lesson'][$taValue['l_id']]['classhour'][$taValue['cl_id']]['tache'][$taValue['ta_id']] = $taValue;
                }

                foreach ($activity as $aKey => $aValue) {
                    $tmp = explode(',', $aValue['act_rel']);
                    $res = array();
                    foreach ($tmp as $tKey => $tValue) {
                        $res[$tValue] = $topic[$tValue];
                    }

                    $aValue['topic'] = $res;
                    $course['lesson'][$aValue['l_id']]['classhour'][$aValue['cl_id']]['tache'][$aValue['ta_id']]['activity'][$aValue['act_id']] = $aValue;
                }

                // 得到XML数据，并写入到xml存放目录
                $xml = xml_encode($course);

                if (file_exists($downLoadPath . $downLoadName . '.' . $ext)) {
                    unlink($downLoadPath . $downLoadName . '.' . $ext);
                }

                file_put_contents($downLoadPath . $downLoadName . '.' . $ext, $xml);
            }

            // 导出成功后，还得更新该课程的co_xml_time
            $save['co_xml_time'] = time();
            M('Course')->where($where)->save($save);

        // type值为2是导出成html格式
        } elseif ($type == 2) {

            $downLoadName = $course['co_title'];
            if ($course['co_version'] != 0) {
                $versionName = C('VERSION_TYPE');
                $downLoadName = $course['co_title'] . '(' . $versionName[$course['co_version']] . ')';
            }
            $downLoadPath = C('EXPORT_HTML_PATH') . $course['co_id'] . '/';
            $ext = 'zip';

            // 导出html前先比较下co_html_time和co_updated，前者大于后者，就直接复制
            if (true || !is_dir($downLoadPath)) {

                // 拷贝目录
                $src = C('EXPORT_HTML_PATH') . '-1/';
                $dst = C('EXPORT_HTML_PATH') . $co_id . '/';

                if (is_dir($downLoadPath)) {
                    deleteDirectory($downLoadPath);
                }

                copyFun($src, $dst);
                $dst .= 'Data/';

                // 查询课程下的课文
                $lesson = M('Lesson')->where($where)->select();

                // string变量用于存放单元
                $string = '';
                foreach ($lesson as $value) {
                    if ($value['l_pid'] == 0) {
                        $string .= "<li rel='" . $value['l_id'] . "'><span class='tree_switch tree_switch_plus unit_switch'></span><a class='tree_unit_a parent_node_a' rel='" . $value['l_id'] . "'><span class='tree_branch' rel='" . $value['l_id'] . "' title='" . $value['l_title'] . "'>" . $value['l_title'] . "</span></a><ul class='ctree ctree_drag i-sortable'></ul></li>";
                    }
                }
                $this->saveJsonData($lesson, $dst, 'lesson');

                // 查询课程下的课时
                $classhour = M('Classhour')->where(array('cl_status' => 1, 'l_id' => array('IN', implode(',', getValueByField($lesson, 'l_id')))))->select();

                // 获取课时下的资源ID
                $ar_id = array();
                foreach ($classhour as &$value) {
                    $value['cl_created'] = date('Y-m-d', $value['cl_created']);
                    if ($value['ar_id']) {
                        array_push($ar_id, trim($value['ar_id'], ','));
                    }
                }
                $this->saveJsonData($classhour, $dst, 'classhour');

                // 查询课时下的环节
                $tache = M('Tache')->where(array('cl_id' => array('IN', implode(',', getValueByField($classhour, 'cl_id')))))->select();
                $this->saveJsonData($tache, $dst, 'tache');

                // 查询环节下的活动
                $activity = M('Activity')->where(array('ta_id' => array('IN', implode(',', getValueByField($tache, 'ta_id')))))->select();

                // 查询活动下的题目
                $topic = M('Topic')->where(array('to_id' => array('IN', implode(',', getValueByField($activity, 'act_rel')))))->select();
                foreach ($topic as &$value) {
                    $value['to_title'] = htmlspecialchars_decode(stripslashes($value['to_title']));
                }
                $this->saveJsonData($topic, $dst, 'topic');

                // 查询活动里的下附件
                $li_id = array();
                $act_id = array();
                foreach ($activity as $ak) {
                    if ($ak['act_type'] == 1 || $ak['act_type'] == 2) {
                        array_push($act_id, $ak['act_id']);
                    } elseif ($ak['act_type'] == 4) {
                        array_push($li_id, $ak['act_rel']);
                    } elseif ($ak['act_type'] == 5) {
                        array_push($ar_id, $ak['act_rel']);
                    }
                }
                $attachment = setArrayByField(M('ActivityAttachment')->where(array('act_id' => array('IN', $act_id)))->field('act_id,ar_id')->select(), 'act_id');
                foreach ($attachment as $at) {
                    array_push($ar_id, trim($at['ar_id'], ','));
                }
                foreach ($activity as &$ak1) {
                    $ak1['ar_id'] = $attachment[$ak1['act_id']]['ar_id'];
                }

                $this->saveJsonData($activity, $dst, 'activity');

                // 查询活动下的外链
                $link = M('Link')->where(array('li_id' => array('IN', implode(',', $li_id))))->select();
                $this->saveJsonData($link, $dst, 'link');

                // 查询课时里的资源
                $authResource = M('AuthResource')->where(array('ar_id' => array('IN', implode(',', $ar_id))))->select();
                $replace = C('TMPL_PARSE_STRING');

                $rule = C('RESOURCE_SAVE_RULES');
                $to = C('EXPORT_HTML_PATH') . $co_id . '/Resource/';

                foreach ($authResource as $key => &$value) {
                    $value['ar_upload'] = str_replace(array_values($replace), array_keys($replace), getResourceImg($value));

                    // 图片
                    if ($value['m_id'] == 1) {

                        // 缩略图 100*75
                        copy($value['ar_upload'], $to . basename($value['ar_upload']));

                        // 缩略图 600*450
                        copy(str_replace('/100/', '/600/', $value['ar_upload']), $to . '600/' . basename($value['ar_upload']));

                        $value['ar_upload'] = './Resource/' . $value['ar_savename'];

                    // 文档
                    } elseif ($value['m_id'] == 4) {
                        if ($value['ar_is_transform'] == 1) {

                            // 源文件
                            copy('../Uploads/AuthResource/transform/document/' . date($rule, $value['ar_created']) . '/' . $value['ar_savename'], $to . $value['ar_savename']);

                            // swf文件
                            $swf = pathinfo($value['ar_savename']);
                            copy('../Uploads/AuthResource/transform/document/' . date($rule, $value['ar_created']) . '/' . $swf['filename'] . '.swf', $to . $swf['filename'] . '.swf');
                        }
                        $value['ar_upload'] = './ResourceImg/' . basename($value['ar_upload']);

                    // 视频
                    } elseif ($value['m_id'] == 2) {
                        if ($value['ar_is_transform'] == 1) {
                            copy('../Uploads/AuthResource/transform/video/' . date($rule, $value['ar_created']) . '/' . $value['ar_savename'], $to . $value['ar_savename']);
                        }
                        $value['ar_upload'] = './ResourceImg/' . basename($value['ar_upload']);

                    // 音频
                    } elseif ($value['m_id'] == 3) {
                        copy('../Uploads/AuthResource/transform/audio/' . date($rule, $value['ar_created']) . '/' . $value['ar_savename'], $to . $value['ar_savename']);
                        $value['ar_upload'] = './ResourceImg/' . basename($value['ar_upload']);
                    }
                }
                $this->saveJsonData($authResource, $dst, 'resource');

                // 替换index.html
                $content = file_get_contents(C('EXPORT_HTML_PATH') . $co_id . '/index.html');
                if ($course['co_version'] != 0) {
                    $versionName = C('VERSION_TYPE');
                    $course['co_title'] = $course['co_title'] . '(' . $versionName[$course['co_version']] . ')';
                }
                $content = str_replace('{$course.co_title}', $course['co_title'], $content);
                $content = str_replace('<li attr=""></li>', $string, $content);
                $content = str_replace('{$title}', $course['co_title'], $content);
                $content = str_replace('{$course.co_id}', $course['co_id'], $content);
                file_put_contents($downLoadPath . 'index.html', $content);

                // 导出成功后，还得更新该课程的co_html_time
                $save['co_html_time'] = time();

                // 压缩成zip文件
                $zipName = $co_id . '.' . $ext;
                zip($downLoadPath.$zipName, $downLoadPath);
                $downLoadName = iconv('utf-8', 'gbk', $downLoadName);
                rename($downLoadPath.$zipName, $downLoadPath . $downLoadName . '.' . $ext);
            }

            $save['co_html_time'] = time();
            M('Course')->where($where)->save($save);
        }

        download($downLoadPath, $downLoadName, $ext);
    }

    // 生成json数据，存放到txt文件内
    public function saveJsonData($data, $path, $fileName, $ext = '.txt') {
        $json = json_encode($data);
        file_put_contents($path . $fileName . $ext, $json);
    }

    // 克隆课程
    public function cloneCourse() {

        // 接收课程ID
        if (!intval($_POST['co_id'])) {
            $this->error('非法操作');
        }

        // 权限检测
        $where['a_id'] = $this->authInfo['a_id'];
        $where['co_id'] = intval($_POST['co_id']);
        $course = $this->checkOwner($where, 'Course');

        // 克隆课程
        extract($course);
        M()->query("INSERT INTO dkt_course VALUES (null,$a_id,$s_id,$ma_id,'" . addslashes($co_title) . "','$co_count','$co_type','$co_grade','$co_semester','$co_subject','$co_version','$co_cover','$co_note','$co_share','0','','','0','0','0','" . time() . "','0')");
        $coId = mysql_insert_id();

        // 克隆课文
        $lesson = M('Lesson')->where($where)->select();
        $tmpLesson = array();
        $lPid = array();
        foreach ($lesson as $key => $value) {
            extract($value);
            M()->query("INSERT INTO dkt_lesson VALUES (null,$d_id,$coId,$a_id,$l_pid,$l_sort,'" . addslashes($l_title) ."'," . time() . ")");
            $tmpLesson[$value['l_id']] = mysql_insert_id();
            if ($l_pid == 0) {
                $lPid[$l_id] = $tmpLesson[$value['l_id']];
            }
        }

        // 重新恢复克隆后的单元和其下课文的关系
        $lessonSub = M('Lesson')->where(array('l_id' => array('IN', $tmpLesson)))->select();
        foreach ($lessonSub as $key => &$value) {
            if ($value['l_pid'] && array_key_exists($value['l_pid'], $lPid)) {
                M('Lesson')->where(array('l_id' => $value['l_id']))->save(array('l_pid' => $lPid[$value['l_pid']]));
            }
        }

        // 克隆课时
        $classhour = M('Classhour')->where(array('cl_status' => 1, 'l_id' => array('IN', implode(',', getValueByField($lesson, 'l_id')))))->select();
        $tmpClasshour = array();
        foreach ($classhour as $key => $value) {
            extract($value);
            M()->query("INSERT INTO dkt_classhour VALUES (null,$coId," . $tmpLesson[$l_id] . ",$a_id,$s_id,'" . addslashes($cl_title) ."',$cl_sort,'','','$ar_id',0,1," . time() . ",0)");
            $tmpClasshour[$cl_id] = mysql_insert_id();
        }

        // 克隆环节
        $tache = M('Tache')->where(array('cl_id' => array('IN', implode(',', getValueByField($classhour, 'cl_id')))))->select();
        $tmpTache = array();
        foreach ($tache as $key => $value) {
            extract($value);
            M()->query("INSERT INTO dkt_tache VALUES (null,$a_id,$coId," . $tmpLesson[$l_id] . "," . $tmpClasshour[$cl_id] . ",'" . addslashes($ta_title) . "','$act_id','$ta_sort'," . time() . ",0,0)");
            $tmpTache[$ta_id] = mysql_insert_id();
        }

        // 克隆活动
        $activity = M('Activity')->where(array('ta_id' => array('IN', implode(',', getValueByField($tache, 'ta_id')))))->select();
        $tmpActivity = array();
        foreach ($activity as $key => $value) {
            extract($value);
            M()->query("INSERT INTO dkt_activity VALUES (null,$s_id,$a_id,$coId," . $tmpLesson[$l_id] . "," . $tmpClasshour[$cl_id] . "," . $tmpTache[$ta_id] . ",'','','" . addslashes($act_title) . "','$act_rel',$act_is_comment,'" . addslashes($act_note) . "',$act_type,0,'',0,1,1,1,0,9,'$act_sort'," . time() . ",0,0)");

            $idsss =  mysql_insert_id();
            // 生成图片
            if ($act_type == 3) {
                generationImg(C('ACTIVITY_TMP_PATH'), $idsss, $act_title, $act_note);
            }
            $tmpActivity[$act_id] = $idsss;
        }

        // 更新环节表下的活动ID
        $tache = M('Tache')->where(array('ta_id' => array('IN', $tmpTache)))->select();
        foreach ($tache as $key => &$value) {
            if ($value['act_id']) {
                $tmp = explode(',', $value['act_id']);
                foreach ($tmp as $k => &$v) {
                    if (array_key_exists($v, $tmpActivity)) {
                        $v = $tmpActivity[$v];
                    }
                }
                $value['act_id'] = implode(',', $tmp);
                M('Tache')->where(array('ta_id' => $value['ta_id']))->save(array('act_id' => $value['act_id']));
            }
        }

        // 克隆活动附件表
        $activityAttachment = M('ActivityAttachment')->where(array('act_id' => array('IN', array_keys($tmpActivity))))->select();
        foreach ($activityAttachment as $key => $value) {
            extract($value);
            M()->query("INSERT INTO dkt_activity_attachment VALUES (null,$s_id,$a_id,$coId," . $tmpLesson[$l_id] . "," . $tmpClasshour[$cl_id] . "," . $tmpTache[$ta_id] . ",'" . $tmpActivity[$act_id] . "',0,'$ar_id','$act_type'," . time() . ",0,0)");
        }

        $this->success($coId);
    }

    // 获取css里的图片
    public function getImg() {

        $handle = fopen('./Public/Css/Home/hour.css', 'r');
        while (!feof($handle)) {
          $buffer = fgets($handle, 1024);
          if (strpos($buffer, 'url(') !== FALSE) {
              preg_match_all('/\((.*)\)/',$buffer,$arr);
              $src = trim(trim($arr[1][0], "'"), '"');
              $to = '../Uploads/Export/html/1111/' . basename($src);
              copy('.' . $src, $to);
          }
        }
    }
}