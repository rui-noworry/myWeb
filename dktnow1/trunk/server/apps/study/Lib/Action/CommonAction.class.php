<?php
/**
 * CommonAction
 * Ilc基类
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */

class CommonAction extends Action {

    // 初始化
    public function _initialize() {

        // 判断是否登录
        if (!isLogin()) {
            $this->redirect('/Index/');
        }

        // 缓存数据
        cacheData();

        $this->authInfo = member();

        // 判断是否有学校管理权限
        if (!$this->authInfo['a_is_manager']) {
            $this->redirect('/Index');
        } else {
            $this->saveAccessList();
        }

        $school = loadCache('school');

        if (strpos($school[$this->authInfo['s_id']]['s_apps'], '1') === FALSE) {
            $this->error('您所在的学校未开通学习平台');
        }

        if (strpos($school[$this->authInfo['s_id']]['s_apps'], '2') !== FALSE) {
            $this->resourceOn = C('RESOURCE_URL');
        }

        // 调用自动毕业方法
        $flag = autoGraduate($this->authInfo['s_id']);

        if ($flag == 0) {
            $this->error('正在执行学期初始化,请稍后访问');
        }

        // 验证学校管理员权限
        if (!$this->accessDecision()) {

            // 没有权限 抛出错误
            if (C('RBAC_ERROR_PAGE')) {

                // 定义权限错误页面
                redirect(C('RBAC_ERROR_PAGE'));
            } else {

                if (C('GUEST_AUTH_ON')){
                    $this->assign('jumpUrl', PHP_FILE.C('USER_AUTH_GATEWAY'));
                }

                // 提示错误信息
                $this->error('没有权限');
            }
        }

        // 模板赋值
        $schoolNode = loadCache('schoolNode');

        $this->module = MODULE_NAME;

        // 获取当前一级模块下的子模块
        foreach ($schoolNode as $key => $value) {
            if (ucfirst(strtolower($value['sn_name'])) == ucfirst(strtolower($this->module))) {
                $second = ($value['sn_pid'] == 0) ? $_SESSION['_SCHOOL_ACCESS_LIST'][$value['sn_id']] : $_SESSION['_SCHOOL_ACCESS_LIST'][$value['sn_pid']];

                foreach ($second as $seKey => $seValue) {

                    // 如果该校没有大学，则隐藏掉专业设置
                    if (substr($school[$this->authInfo['s_id']]['s_type'], -1) != 4 && $schoolNode[$seKey]['sn_name'] == 'Major') {
                        continue;
                    }

                    $secondList[$seKey] = $schoolNode[$seKey];
                }
            }
        }

        $this->secondList = $secondList;

        // 获取所有一级模块
        foreach ($_SESSION['_SCHOOL_ACCESS_LIST'][0] as $salKey => $salValue) {
            $allowNode[$salKey] = $schoolNode[$salKey];
        }

        $this->allowNode = $allowNode;

        flipParam();

        $this->bannerOn = 1;
    }

    // 缓存权限
    public function saveAccessList($name='_SCHOOL_ACCESS_LIST') {

        if (!$_SESSION[$name]) {
            $this->getAccessList();
        }
        return ;
    }

    //用于检测用户权限的方法,并保存到Session中
    public function getAccessList($name = '_SCHOOL_ACCESS_LIST') {

        // 获取学校管理权限组ID
        $srIds = M('SchoolRoleUser')->where(array('s_id' => $this->authInfo['s_id'], 'a_id' => $this->authInfo['a_id']))->field('sr_id')->select();

        // 是否学校超级管理员
        foreach ($srIds as $srValue) {
            if ($srValue['sr_id'] != 0) {
                $schoolRole[] = $srValue['sr_id'];
            } else {
                $schoolAdmin = 1;
            }
        }

        $schoolRole = M('SchoolRole')->where(array('sr_id' => array('IN', implode(',', $schoolRole)), 's_id' => $this->authInfo['s_id'], 'sr_status' => 1))->getField('sr_id', TRUE);

        $schoolNode = loadCache('schoolNode');

        $snIds = M('SchoolAccess')->where(array('sr_id' => array('IN', implode(',', $schoolRole))))->getField('sn_id', true);

        foreach ($schoolNode as $snKey => $snValue) {

            if ($schoolAdmin || in_array($snKey, $snIds)) {
                $access[$snValue['sn_pid']][$snValue['sn_id']] = 1;
            }
        }

        $_SESSION[$name] = $access;
    }

    public function accessDecision($name = '_SCHOOL_ACCESS_LIST') {

        // 如果当前操作已经认证过，无需再次认证
        $schoolNode = loadCache('schoolNode');

        foreach ($schoolNode as $snValue) {
            if (strtoupper($snValue['sn_name']) == strtoupper(MODULE_NAME)) {
                $allow = $snValue;
            }
        }

        if ($allow['sn_pid']) {
            $tmp = $allow['sn_pid'];
        } else {

            $flag = false;
            foreach ($_SESSION[$name][$allow['sn_id']] as $aKey => $aValue) {
                if ($schoolNode[$aKey]['sn_url'] == $schoolNode[$allow['sn_id']]['sn_url']) {
                    $flag = true;break;
                }
            }

            if ($flag) {
                $tmp = $allow['sn_id'];
            } else {
                $tmp = $_SESSION[$name][$allow['sn_id']][$allow['sn_id']];
            }
        }

        $this->leftOn = $tmp;
        if ($allow && $tmp) {
            return true;
        }

        if ($_SESSION[$name] && !$tmp) {

            foreach ($_SESSION[$name][0] as $key => $value) {
                $tmp = $key;break;
            }
            foreach ($_SESSION[$name][$tmp] as $sKey => $sValue) {
                $tmp = $sKey;break;
            }

            $this->redirect($schoolNode[$tmp]['sn_url']);exit;
        }

        return false;
    }

    // 默认写入数据
    public function insertData() {

        $model = D($this->getActionName());

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        return $model->add();
    }

    // 默认写入操作
    public function insert() {

        //保存当前数据对象
        $result = $this->insertData();
        $this->show($result);
    }

    // 默认显示操作
    public function show($result) {

        //保存当前数据对象
        if (false !== $result) {

            $jumpUrl = '__APPURL__/' . GROUP_NAME . '/' . $this->getActionName() . '/index';
            //成功提示
            $this->assign('jumpUrl', $jumpUrl);
            $this->success('成功');
        } else {

            //失败提示
            $this->error('失败');
        }
    }

    // 默认更新数据
    public function updateData() {

        $model = D($this->getActionName());

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        // 更新数据
        return $model->save();
    }

    // 默认更新操作
    public function update() {

        // 更新数据
        $result == $this->updateData();

        $this->show($result);
    }

    // 默认列表页
    public function index() {

        // 列表过滤器，生成查询Map对象
        $map = $this->_search();

        // 条件过滤
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }

        $model = M($this->getActionName());

        // 获取列表数据
        if (!empty($model)) {
            $this->_list($model, $map, $_REQUEST['field'], $_REQUEST['sortby']);
        }

        Cookie('__forward__', $_SERVER['REQUEST_URI']);

        $this->display();
    }

     // 根据表单生成查询条件和过滤
    protected function _search($name='') {

        //生成查询条件
        $name  = $name? $name: $this->getActionName();

        $model = M($name);
        $map   = array();

        foreach($model->getDbFields() as $key => $val) {

            if (substr($key, 0, 1)=='_') continue;

            if (isset($_REQUEST[$val]) && $_REQUEST[$val]!='') {

                $map[$val] = $_REQUEST[$val];
            }
        }
        return $map;
    }

    // 列表数据
    protected function _list($model, $map = array(), $sortBy = '', $asc = false) {

        // 排序字段 默认为主键名
        if (isset($_REQUEST['_order'])) {

            $order = $_REQUEST['_order'];
        } else {

            $order = !empty($sortBy)? $sortBy: $model->getPk();
        }

        // 排序方式默认按照倒序排列
        // 接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset($_REQUEST['_sort'])) {

            $sort = $_REQUEST['_sort']? 'ASC': 'DESC';
        } else {

            $sort = $asc? 'ASC': 'DESC';
        }

        // 取得满足条件的记录数
        $count = $model->where($map)->count();

        // 加载分页类
        import("@.ORG.Util.Page");

        if (!empty($_REQUEST['listRows'])) {

            $listRows = $_REQUEST['listRows'];
        } else {

            $listRows = '';
        }

        $p = new Page($count, $listRows);

        // 分页查询数据
        $voList = $model->where($map)->order($order.' '.$sort)->limit($p->firstRow . ',' . $p->listRows)->select();

        // 分页跳转的时候保证查询条件
        foreach($map as $key => $val) {

            if (!is_array($val)) {
                $p->parameter .= "$key=" . urlencode($val) . "&";
            }
        }

        // 分页显示
        $page   = $p->show();

        // 列表排序显示
        $sortImg= $sort ;                                   //排序图标
        $sortAlt= $sort == 'DESC'?'升序排列':'倒序排列';    //排序提示
        $sort   = $sort == 'DESC'? 1:0;                     //排序方式

        // 模板赋值显示
        $this->assign('list', $voList);
        $this->assign('sort', $sort);
        $this->assign('order', $order);
        $this->assign('sortImg', $sortImg);
        $this->assign('sortType', $sortAlt);
        $this->assign("page", $page);

        Cookie('_currentUrl_',__SELF__);

        return ;
    }

    // 默认删除操作
    public function deleteData() {

        $model = M($this->getActionName());

        // 获取主键
        $pk = $model->getPk();

        if (!empty($model)) {

            // 接收参数
            $id = strval($_REQUEST['id']);

            if (isset($id)) {

                // 条件
                $condition = array($pk => array('IN', $id));

                return $model->where($condition)->delete();

            } else {
                $this->error('非法操作');
            }
        }
    }

    // 默认删除操作
    public function delete() {

        $result = $this->deleteData();

        $this->show($result);
    }

    // 默认禁用操作
    public function forbid($model = '') {

        if (!$model){
            $model = D($this->getActionName());
        }

        // 获取主键
        $pk = $model->getPk();

        // 接收参数
        $id = strval($_REQUEST['id']);

        // 组织条件
        $condition = array($pk => array('IN', $id));
        $pre = substr($pk, 0, strpos($pk, '_')) . '_';
        $field = $pre . 'status';

        if ($model->forbid($condition, $field)) {

            $this->assign('jumpUrl', '/Ilc/' . $this->getActionName() . '/index');
            $this->success('状态禁用成功！');
        } else {

            $this->error('状态禁用失败！');
        }
    }

    // 默认启用操作
    public function resume($model = '') {

        if (!$model){
            $model = D($this->getActionName());
        }

        // 获取主键
        $pk = $model->getPk();

        // 条件
        $id = strval($_REQUEST['id']);
        $condition = array($pk => array('IN', $id));

        $pre = substr($pk, 0, strpos($pk, '_')) . '_';
        $field = $pre . 'status';

        if ($model->resume($condition, $field)) {

            $this->assign('jumpUrl', '/Ilc/' . $this->getActionName() . '/index');
            $this->success('启用成功！');
        } else {

            $this->error('启用失败！');
        }
    }

    // 默认编辑操作
    public function edit() {

        $model = M($this->getActionName());

        // 获取数据
        $vo = $model->find(intval($_REQUEST['id']));

        $this->assign('vo', $vo);
        $this->display();
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
        //设置上传文件规则
        $upload->saveRule = 'uniqid';
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $info = $upload->getUploadFileInfo();
            return $info[0]['savename'];
        }
    }
}

?>