<?php
/**
 * LessonAction
 * 课文类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-6
 *
 */
class LessonAction extends BaseAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();
        $this->template = strtolower(ACTION_NAME).$this->authInfo['a_type'];
    }

    public function index() {

        // 接收课程id
        $id = intval($_GET['course']);

        // 没有接收到课程id，便转到课程列表页
        if (!$id) {
            $this->redirect('/Course');
        }

        // 班级ID
        $this->c_id = intval($_GET['c_id']);

        // 群组ID
        $this->cro_id = intval($_GET['cro_id']);

        // 预防url上既有c_id又有cro_id
        if ($this->c_id != 0 && $this->cro_id !=0) {
            $this->redirect('/Index');
        }

        // 查询课程创建人是否是登录者
        $this->course = $this->checkOwner(array('co_id' => $id), 'Course');

        // 实时显示课程加班级或群组名称
        $title = $this->course['co_title'];
        if ($this->c_id) {
            $class = M('Class')->where(array('c_id' => $this->c_id))->find();
            if (!$class) {
                $this->redirect('/Index');
            }
            $title = replaceClassTitle($class['s_id'], $class['c_type'], YearToGrade($class['c_grade'], $class['s_id']), $class['c_title'], $class['c_is_graduation']) . ' >> ' . $title;
        }
        if ($this->cro_id) {
            $cro_title = M('Crowd')->where(array('cro_id' => $this->cro_id))->getField('cro_title');
            if (!$cro_title) {
                $this->redirect('/Index');
            }
            $title = $cro_title . ' >> ' . $title;
        }

        $this->title = $title;

        // 查询是否有课文
        $this->lesson = M('Lesson')->where(array('co_id' => $id, 'l_pid' => 0))->field('l_id,co_id,a_id,l_sort,l_title')->order('l_sort DESC')->select();

        // 获取该课程指定的班级和群组
        $this->bindInfo = D('Lesson')->getBindClassAndGroup($this->authInfo['a_id'], $this->authInfo['s_id'], $this->course['c_id'], $this->course['cro_id']);

        // 活动类型
        $this->type = C('OBJECT_TYPE');

        // 取出题库中点击量前45个标签
        $topicTerm = M('TopicTerm')->order('tt_count DESC')->limit(45)->select();
        $this->topicTerm = array_chunk($topicTerm, 15, TRUE);

        // 获取模型列表
        $this->model = loadCache('model');
        $this->maxSize = intval(ini_get('upload_max_filesize'));
        $this->display($this->template);
    }

    // 动态获取课文列表
    public function lists() {

        // 检测
        if (!intval($_POST['co_id']) || !intval($_POST['l_id'])) {
            $this->error('非法操作');
        }

        $_POST['l_pid'] = intval($_POST['l_id']);
        unset($_POST['l_id']);

        // 如果传来了课文标题，说明是页面在没有异步加载时便添加课文
        // 需要先添加课文，在展示列表
        if (strval($_POST['l_title'])) {
            $_POST['flag'] = TRUE;
            $this->insert();
        }

        // 查询单元下的课文
        $lesson = M('Lesson')->where(array('co_id' => intval($_POST['co_id']), 'l_pid' => intval($_POST['l_pid'])))->field('l_id,co_id,a_id,l_sort,l_title')->order('l_sort DESC,l_id ASC')->select();

        echo json_encode($lesson);

    }

    // 导入课程表
    public function import() {

        // 接收课程id，组织where条件
        $data['co_id'] = intval($_POST['co_id']);
        $data['a_id'] = $this->authInfo['a_id'];

        // 查询课程创建人是否是登录者
        $course = $this->checkOwner($data, 'Course');

        // 查询课文表下是否有教师的课文数据
        $lesson = M('Lesson')->where($data)->find();
        if (!empty($lesson)) {
            $this->error('请清空课文');
        }

        // 查询课文目录表的条件
        $data['d_version'] = $course['co_version'];
        $data['d_subject'] = $course['co_subject'];
        $data['d_name'] = array('neq', '');
        $data['d_code'] = $course['co_type'] . $course['co_grade'] . $course['co_semester'];

        // 查询课文目录表
        $directory = M('Directory')->where($data)->order('d_pid asc ,d_id asc')->select();
        if (empty($directory)) {
            $this->error('未找到符合要求的数据');
        }

        // 插入数据,首先清掉post过来的数据，然后自己组织数据循环插入到课文表
        foreach ($directory as $key => $value) {
            if ($value['d_pid'] == 0) {

                unset($_POST);
                $_POST['d_id'] = $value['d_id'];
                $_POST['co_id'] = $data['co_id'];
                $_POST['a_id'] = $data['a_id'];
                $_POST['l_pid'] = 0;
                $_POST['l_sort'] = 0;
                $_POST['l_title'] = $value['d_name'];
                $_POST['l_created'] = time();

                // Directory的ID 对应 Lesson的ID
                $tmp[$value['d_id']] = $this->insertData();
            }
        }

        foreach ($directory as $key => $value) {

            if ($value['d_pid'] != 0) {
                unset($_POST);
                $_POST['d_id'] = $value['d_id'];
                $_POST['co_id'] = $data['co_id'];
                $_POST['a_id'] = $data['a_id'];
                $_POST['l_pid'] = $tmp[$value['d_pid']];
                $_POST['l_sort'] = 0;
                $_POST['l_title'] = $value['d_name'];
                $_POST['l_created'] = $value['d_created'];

                $this->insertData();
            }
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $data['co_id']))->save(array('co_updated' => time()));

        $this->success('导入成功');
    }

    // 更新课文
    public function update() {

        // 接收参数
        $where['a_id'] = $this->authInfo['a_id'];
        $where['co_id'] = intval($_POST['co_id']);

        // 验证登陆用户是否为创建人
        $course = $this->checkOwner($where, 'Course');

        // 如果接收过来的是l_sort，那么说明页面上执行的是拖拽的动作
        if (isset($_POST['l_sort'])) {
            $data = array_reverse(explode(',', trim($_POST['l_sort'], ',')));
            foreach ($data as $key => $value) {
                $where['l_id'] = $value;
                $result = M('Lesson')->where($where)->save(array('l_sort' => $key));
            }
        }

        // 如果接收过来的是l_title，那么说明页面上执行的编辑的动作
        if (isset($_POST['l_title'])) {
            $where['l_id'] = intval($_POST['l_id']);
            $data['l_title'] = $_POST['l_title'];
            $result = M('Lesson')->where($where)->save($data);
        }

        if (!$result) {
            $this->error('更新失败!');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $where['co_id']))->save(array('co_updated' => time()));
        $this->success('更新成功!');
    }

    // 添加课文
    public function insert() {

        // 组织数据
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['l_created'] = time();
        $_POST['l_sort'] = 0;

        // 权限验证
        $course = $this->checkOwner($_POST, 'Course');

        $result = $this->insertData();

        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));

        // 把新添加的课文id给返回过去
        if (!$_POST['flag']) {
            $this->success($result);
        }
    }

    // 删除课文
    public function delete() {

        // 接收参数
        $check['l_id'] = $_POST['l_id'];
        $check['co_id'] = $_POST['co_id'];
        $check['a_id'] = $this->authInfo['a_id'];

        // 身份验证
        $this->checkOwner($check, 'Course');

        // 单元删除时，需要做验证
        $tmp = $check['l_id'];
        unset($check['l_id']);
        $check['l_pid'] = $tmp;
        $lesson = M('Lesson')->where($check)->select();
        if ($lesson) {
            $this->error('清空单元下的课文');
        }

        // 查询课文下是否有课时
        unset($check['l_pid']);
        $check['l_id'] = $tmp;
        $check['cl_status'] = 1;
        $class = M('Classhour')->where($check)->select();

        // 有课时便不能删除
        if ($class) {
            $this->error('请先清空该课文下的课时');
            exit;
        }

        // 删除课文
        $result = M('Lesson')->where($check)->delete();

        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));
        $this->success('操作成功');
    }


}