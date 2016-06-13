<?php
/**
 * Classhour
 * 课时类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class ClasshourAction extends BaseAction {

    // ajax输出课时，展现到页面上
    public function index() {

        // 接收参数，课程id和课文id
        $data['co_id'] = intval($_POST['co_id']);
        $data['l_id'] = intval($_POST['l_id']);
        $this->checkOwner($data, 'Lesson');

        if ($this->authInfo['a_type'] == 2) {

            $data['a_id'] = $this->authInfo['a_id'];
            $data['cl_status'] = 1;
            $classhour = setArrayByField(M('Classhour')->where($data)->select(), 'cl_id');

            // 查询课时发布表中该课文下所有发布的课时
            $publish = M('ClasshourPublish')->where($data)->field('cl_id,c_id,cro_id,cp_created')->select();
            $cId = getValueByField($publish, 'c_id');
            $gId = getValueByField($publish, 'cro_id');

            // 班级
            if ($cId) {
                $whereC['c_id'] = array('IN', implode(',', $cId));
                $whereC['s_id'] = $this->authInfo['s_id'];
                $class = setArrayByField(M('Class')->where($whereC)->field('s_id,c_type,c_grade,c_title,c_is_graduation,c_id')->select(), 'c_id');
                foreach ($class as $key => &$value) {
                    $value['c_title'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation']);
                }
            }

            // 群组
            if ($gId) {
                $whereG['cro_id'] = array('IN', implode(',', $gId));
                $whereG['a_id'] = $this->authInfo['a_id'];
                $crowd = setArrayByField(M('Crowd')->where($whereG)->field('cro_id, cro_title')->select(), 'cro_id');
            }

            foreach ($publish as $key => &$value) {
                $value['cp_created'] = date('Y-m-d', $value['cp_created']);
                $value['class_title'] = $class[$value['c_id']]['c_title'];
                $value['crowd_title'] = $crowd[$value['cro_id']]['cro_title'];
                $value['cl_title'] = $classhour[$value['cl_id']]['cl_title'];
            }
            $tmp['publish'] = $publish;
        } else {

            // 接收群组ID
            if (intval($_POST['cro_id'])) {
                $data['cro_id'] = intval($_POST['cro_id']);
            }

            // 接收班级ID
            if (intval($_POST['c_id'])) {
                $data['c_id'] = intval($_POST['c_id']);
            }

            $classhour = M('ClasshourPublish')->where($data)->select();

            // 获取课时信息
            $classhourInfo = getDataByArray('Classhour', $classhour, 'cl_id', 'cl_id, cl_title');

            // 显示课时标题
            foreach ($classhour as $key => $value) {
                $classhour[$key]['cl_title'] = $classhourInfo[$value['cl_id']]['cl_title'];
                $classhour[$key]['cl_created'] = $value['cp_created'];
            }

        }

        sort($classhour);
        $tmp['classhour'] = $classhour;

        foreach ($tmp['classhour'] as $key => &$value) {
            $tmp['classhour'][$key]['cl_created'] = date('Y-m-d', $value['cl_created']);
        }

        echo json_encode($tmp);
    }

    // 添加课时
    public function insert() {

        // 验证课文所有者
        $where['l_id'] = intval($_POST['l_id']);
        $where['co_id'] = intval($_POST['co_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $this->checkOwner($where, 'Lesson');

        // 接收数参
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['cl_created'] = time();

        $result = $this->insertData();

        if (!$result) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $where['co_id']))->save(array('co_updated' => time()));

        $c_ids = M('Course')->where(array('co_id' => $where['co_id']))->getField('c_id');

        foreach (explode(',', $c_ids) as $value) {

            if ($value) {

                // 添加动态
                addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $value, 1, 7, 0, M('Course')->where($where)->getField('co_subject'), strval($_POST['cl_title']), $result);
            }
        }

        if (!$_POST['flag']) {
            $this->success($result);
        }
    }

    // 删除课时
    public function delete() {

        // 验证身份
        $where['a_id'] = $this->authInfo['a_id'];
        $where['cl_id'] = intval($_POST['cl_id']);
        $classhour = $this->checkOwner($where, 'Classhour');

        // 查询该课时cl_is_publish字段，为1不能删除，为0时可以删除
        if ($classhour['cl_is_published'] == 1) {
            $this->error('不能删除已发布的课时');
        }

        // 删除课时，如果在课时下有环节，则把状态置为0，否则就删除
        if (M('Tache')->where(array('cl_id' => $where['cl_id']))->getField('ta_id')) {
            $result = M('Classhour')->where($where)->save(array('cl_status' => 0));
        } else {
            $result = M('Classhour')->where($where)->delete();
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $classhour['co_id']))->save(array('co_updated' => time()));
        $this->success('操作成功');

        if (!$result) {
            $this->error('操作失败');
        }
    }

    // 更新课时
    public function update() {

        // 验证身份
        $where['l_id'] = intval($_POST['l_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $where['cl_id'] = intval($_POST['cl_id']);
        $this->checkOwner($where, 'Lesson');

        // 保存数据
        $data['cl_title'] = $_POST['cl_title'];
        $result = M('Classhour')->where($where)->save($data);

        if ($result === FALSE) {
            $this->error('操作失败');
        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => intval($_POST['co_id'])))->save(array('co_updated' => time()));

        $c_ids = M('Course')->where(array('co_id' => intval($_POST['co_id'])))->getField('c_id');

        foreach (explode(',', $c_ids) as $value) {

            if ($value) {

                // 添加动态
                addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $value, 2, 7, 0, M('Course')->where($where)->getField('co_subject'), strval($_POST['cl_title']), $where['cl_id']);
            }
        }

        $this->success('操作成功');
    }

    // 课时排序
    public function updateSort() {

        // 检测
        if (!intval($_POST['co_id']) || !strval($_POST['cl_sort'])) {
            $this->error('非法操作');
        }

        $where['co_id'] = intval($_POST['co_id']);

        $data = array_reverse(explode(',', strval($_POST['cl_sort'])));
        foreach ($data as $key => $value) {
            $where['cl_id'] = $value;
            M('Classhour')->where($where)->save(array('cl_sort' => $key));
        }
    }

    // 验证url里的班级或群组是否在已绑定的课时里
    public function validateInfo() {

        // 检测
        if (!intval($_POST['cl_id'])) {
            echo 0;
            return;
        }
        $c_id = intval($_POST['c_id']);
        $cro_id = intval($_POST['cro_id']);
        if ($c_id == 0 && $cro_id == 0) {
            echo 0;
            return;
        }

        // 条件
        $where['cl_id'] = intval($_POST['cl_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $where['s_id'] = $this->authInfo['s_id'];

        // 获取课时下绑定的班级和群组
        $classhour = M('Classhour')->where($where)->field('c_id,cro_id')->find();
        if (!$classhour) {
            echo 0;
            return;
        }

        // 判断
        if ($c_id && strpos($classhour['c_id'], ',' . $c_id . ',') !== FALSE) {
            echo 1;
            return;
        } elseif ($cro_id && strpos($classhour['cro_id'], ',' . $cro_id . ',') !== FALSE) {
            echo 1;
            return;
        } else {
            echo 0;
            return;
        }
    }

    // 动态获取课文下的课时
    public function lists() {

        // 检测
        if (!intval($_POST['co_id']) || !intval($_POST['l_id'])) {
            $this->error('非法操作');
        }

        // 如果传来了课时标题，说明是页面在没有异步加载时便添加课时
        // 需要先添加课时，在展示列表
        if (strval($_POST['cl_title'])) {
            $_POST['flag'] = TRUE;
            $this->insert();
        }

        // 查询单元下的课文
        $classhour = M('Classhour')->where(array('co_id' => intval($_POST['co_id']), 'l_id' => intval($_POST['l_id']), 'cl_status' => 1))->field('cl_id,cl_sort,cl_title,cl_is_published,c_id,cro_id,ar_id')->order('cl_sort DESC,cl_id ASC')->select();

        echo json_encode($classhour);
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

    // 上传课时附件
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

    // lesson页面中点击确定时，把资源入库
    public function insertResource() {

        // 检测
        $cl_id = intval($_POST['cl_id']);
        $re_id = strval($_POST['ar_id']);
        $id = '';
        if (!$cl_id) {
            $this->error('非法操作');
        }
        $classhour = $this->checkOwner(array('a_id' => $this->authInfo['a_id'], 'cl_id' => $cl_id), 'Classhour', 'co_id,ar_id');

        // 过滤已经存在的资源ID
        if ($classhour['ar_id'] && $re_id) {
            $re_id = explode(',', $re_id);
            foreach ($re_id as $rValue) {
                if (strpos($classhour['ar_id'], ',' . $rValue . ',') === FALSE) {
                    $tmp[] = $rValue;
                }
            }
            $re_id = $tmp ? implode(',', $tmp) : '';
        }

        // 如果有上传的资源ID，则入库
        if ($_SESSION['result']) {

            // 把该活动所属课程标签添加或是更新到资源标签表，并以数组形式返回相关的标签ID
            $course = M('Course')->where(array('a_id' => $this->authInfo['a_id'], 'co_id' => $classhour['co_id']))->find();
            foreach ($_SESSION['result'] as $va) {
                $rta_id = turnIdToWord($course, 'ResourceTag');
            }
            $rta_id = ',' . implode(',', $rta_id) . ',';

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

            $ar_id = implode(',', $ar_id);

            unset($_SESSION['result']);
        }

        if ($re_id == '' && $ar_id == '') {
            $this->success('无数据');
        } else {
            $id = implode(',', array_filter(explode(',', $ar_id . ',' . $re_id)));
            $save['ar_id'] = $classhour['ar_id'] ? $classhour['ar_id'] . $id . ',' : ',' . $id . ',';
            $save['cl_updated'] = time();
            M('Classhour')->where(array('a_id' => $this->authInfo['a_id'], 'cl_id' => $cl_id))->save($save);
            $this->success('入库成功');
        }
    }

    // 页面上点击取消后，删除资源的session
    public function unsetSession() {
        unset($_SESSION['result']);
        $this->success('销毁session成功');
    }

    // 异步删除课时下的资源
    public function syncMoveFile() {

        // 验证
        $ar_id = intval($_POST['ar_id']);
        $where['cl_id'] = intval($_POST['cl_id']);
        $where['a_id'] = $this->authInfo['a_id'];
        $where['s_id'] = $this->authInfo['s_id'];
        if (!$ar_id || !$where['cl_id']) {
            $this->error('非法操作');
        }

        $rta_id = M('AuthResource')->where(array('ar_id' => $ar_id))->getField('rta_id');
        if (!$rta_id) {
            $this->error('非法操作');
        }

        // 把资源相关的标签使用次数-1
        M('ResourceTag')->where(array('rta_id' => array('IN', trim($rta_id, ','))))->save(array('rta_count' => array('exp', 'rta_count-1')));

        // 去课时表里解除该资源ID
        $classhour = M('Classhour')->where($where)->find();
        if (!$classhour) {
            $this->error('非法操作');
        }
        $activity = new ActivityAction();
        $save['ar_id'] = $activity->recombineString($classhour['ar_id'], $ar_id, 1);

        // 保存
        $result = M('Classhour')->where($where)->save($save);
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');

    }

    // 课时资源分页
    public function getPage() {

        // 检测
        $where['cl_id'] = intval($_POST['cl_id']);
        $p = intval($_POST['p']);
        if(!$where['cl_id']) {
            $this->error('非法操作');
        }

        $classhour = $this->checkOwner($where, 'Classhour', 'cl_id,ar_id');
        $info = array();
        if ($classhour['ar_id']) {
            $ar_id = array_reverse(explode(',', trim($classhour['ar_id'], ',')));
            $count = count($ar_id);
            $info['totalPage'] = ceil($count/5);
            if ($count > 5) {
                $ar_id = array_chunk($ar_id, 5);
                $where['ar_id'] = array('IN', $ar_id[$p-1]);
            } else {
                $where['ar_id'] = array('IN', $ar_id);
            }
            $info['list'] = M('AuthResource')->where($where)->select();
            foreach ($info['list'] as $key => $value) {
                $info['list'][$key]['ar_upload'] = getResourceImg($value);
            }
        }

        echo json_encode($info);

    }
}