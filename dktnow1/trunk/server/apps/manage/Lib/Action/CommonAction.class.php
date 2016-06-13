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

        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {

            // 加载RBAC
            import('@.ORG.Util.RBAC');

            if (!RBAC::AccessDecision(GROUP_NAME)) {

                // 检查认证识别号
                if (!$_SESSION[C('USER_AUTH_KEY')]) {

                    // 跳转到认证网关
                    redirect(C('USER_AUTH_GATEWAY'));
                }

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
        }

        // 后台初始化标签
        if (C('APP_PLUGIN_ON')) tag('admin_init');

        if (!empty($_GET['forward'])) {

            // 设置返回URL
            Cookie('__forward__',base64_decode($_GET['forward']));
        }

        // 获取栏目
        $list = M('Group')->where(array('g_status' => 1))->order('g_sort')->field('g_id,g_name,g_title,g_url')->select();
        $this->nodeGroupList = $list;

        // 获取子栏目
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            // 显示菜单项
            $menu  = array();
            if (isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {

                // 如果已经缓存，直接读取缓存
                $menu   = $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
            } else {
                // 读取数据库模块列表生成菜单项
                $node   = M("Node");
                $id     = $node->where(array('n_level' => 1, 'n_name' => GROUP_NAME))->getField("n_id");

                $where['n_level'] = 2;
                $where['n_show'] = 1;
                $where['n_status'] = 1;
                $where['n_pid'] = $id;

                $list   = $node->where($where)->field('n_id,n_name,n_url,g_id,n_title')->order('n_sort asc')->select();

                $accessList = $_SESSION['_ACCESS_LIST'];
                foreach($list as $key => $module) {
                     if(isset($accessList[strtoupper(GROUP_NAME)][strtoupper($module['n_name'])]) || !empty($_SESSION['administrator'])) {
                        // 设置模块访问权限
                        $module['access'] =   1;
                        $menu[$key]  = $module;
                    }
                }
                // 缓存菜单访问
                $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]] = $menu;
            }
        }

        $group = M('Group')->where(array('g_status' => 1))->select();

        // 重组数据
        foreach ($menu as $mk => $mv) {
            $menuArr[$mv['g_id']][] = $mv;
        }

        foreach ($group as $gv) {
            $groupArr[$gv['g_id']] = $menuArr[$gv['g_id']];
        }

        $this->menuArr = $groupArr;

        cacheData();
        reloadCache();

    }

    // 默认列表页
    public function index() {

        // 列表过滤器，生成查询Map对象
        $map = $this->_search();

        // 条件过滤
        if (method_exists($this,'_filter')) {
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
    protected function _list($model, $map=array(), $sortBy='', $asc=false) {

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

    // 默认新增操作
    public function add() {

        $this->display();
    }

    // 默认查看操作
    public function read() {

        $this->edit();
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

    // 默认编辑操作
    public function edit() {

        $model = M($this->getActionName());

        // 获取数据
        $vo = $model->find(intval($_REQUEST['id']));

        $this->assign('vo', $vo);
        $this->display();
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

    // 默认列表选择操作
    protected function select($fields='id,name', $title='') {

        $map = $this->_search();

        $Model = M($this->getActionName());

        //查找满足条件的列表数据
        $list = $Model->where($map)->getField($fields);

        // 赋值
        $this->assign('selectName', $title);
        $this->assign('list', $list);
        $this->display();
    }

    // 默认删除操作
    public function deleteData() {

        $model = D($this->getActionName());

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

    // 默认排序操作
    public function sort() {

        $model = M($this->getActionName());

        // 获取主键
        $pk = $model->getPk();
        $pre = substr($pk, 0, strpos($pk, '_')) . '_';

        // 条件
        $conditions = array();
        $conditions[$pre . 'status'] = 1;

        if(!empty($_GET['sortId'])) {

            $conditions[$pk] = array('IN', $_GET['sortId']);
        }

        // 获取数据
        $sortList = $model->where($conditions)->order($pre . 'sort ASC')->select();

        // 模板赋值
        $this->assign("sortList",$sortList);
        $this->display();
    }

    // 默认排序保存操作
    public function saveSort() {

        $seqNoList = $_POST['seqNoList'];

        if(!empty($seqNoList)) {

            $model = M($this->getActionName());

            // 获取主键
            $pk = $model->getPk();
            $pre = substr($pk, 0, strpos($pk, '_')) . '_';
            $sort = $pre . 'sort';

            $col = explode(',', $seqNoList);
            //启动事务
            $model->startTrans();
            foreach ($col as $val) {

                $val = explode(':',$val);

                $model->$pk = $val[0];
                $model->$sort = $val[1];

                $result = $model->save();
                if (false === $result) {
                    break;
                }
            }

            //提交事务
            $model->commit();
            if (false !== $result) {

                //采用普通方式跳转刷新页面
                $this->success('更新成功');
            }else {

                $this->error($model->getError());
            }
        }
    }

    // 通过审核
    public function pass() {

        //删除指定记录
        $model = D($this->getActionName());

        if(!empty($model)) {

            // 获取主键
            $pk = $model->getPk();

            // 接收参数
            $id = strval($_REQUEST['id']);

            // 条件
            $condition = array($pk => array('IN', $id));

            $pre = substr($pk, 0, strpos($pk, '_')) . '_';
            $field = $pre . 'status';

            if (false !== $model->pass($condition, $field)){

                $this->assign("jumpUrl", $this->getReturnUrl());
                $this->success('审核通过！');
            } else {

                $this->error('审核失败！');
            }

        }
    }

    // 默认禁用操作
    public function forbid($model = '') {

        if (!$model){
            $model = D($this->getActionName());
        }

        // 获取主键
        $pk = $model->getPk();

        // 接收参数
        $id = strval($_GET['id']);

        // 组织条件
        $condition = array($pk => array('IN', $id));
        $pre = substr($pk, 0, strpos($pk, '_')) . '_';
        $field = $pre . 'status';

        if ($this->forbidData($condition, $field)) {

            $this->assign('jumpUrl', '__APPURL__/Ilc/' . $this->getActionName() . '/index');
            $this->success('状态禁用成功！');
        } else {

            $this->error('状态禁用失败！');
        }
    }

    // 禁用
    public function forbidData($condition, $field) {

        $data[$field] = 9;

        $res = M($this->getActionName())->where($condition)->save($data);

        return $res;
    }

    // 默认恢复操作
    public function resume($model = '') {

        if (!$model){
            $model = D($this->getActionName());
        }

        // 获取主键
        $pk = $model->getPk();

        // 条件
        $id = strval($_GET['id']);
        $condition = array($pk => array('IN', $id));

        $pre = substr($pk, 0, strpos($pk, '_')) . '_';
        $field = $pre . 'status';

        if ($this->resumeData($condition, $field)) {

            $this->assign('jumpUrl', '__APPURL__/Ilc/' . $this->getActionName() . '/index');
            $this->success('启用成功！');
        } else {

            $this->error('启用失败！');
        }
    }

    // 启用
    public function resumeData($condition, $field) {

        $data[$field] = 1;

        $res = M($this->getActionName())->where($condition)->save($data);

        return $res;
    }

    // 默认还原操作
    public function recycle() {

        $model = D($this->getActionName());
        $pk    = $model->getPk();
        $id    = $_GET[$pk];

        $condition = array($pk => array('IN', $id));

        if ($model->recycle($condition)) {
            $this->assign("jumpUrl", __URL__.'/recycleBin/');
            $this->success('状态还原成功！');
        } else {
            $this->error('状态还原失败！');
        }
    }

    public function recycleBin() {

        $map = $this->_search();
        $map['status'] = -1;
        $model = D($this->getActionName());
        if (!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
    }

    // 默认推荐操作
    function recommend() {

        $model = D($this->getActionName());
        $pk    = $model->getPk();
        $id    = $_GET[$pk];

        $condition = array($pk => array('IN', $id));

        if ($model->recommend($condition)){
            $this->assign('jumpUrl', Cookie('_currentUrl_'));
            $this->success('推荐成功！');
        } else {
            $this->error('推荐失败！');
        }
    }

    function getReturnUrl() {
        return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
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
            $upload->autoSub = true;
            $upload->subType = false;
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

    protected function doRequest($url, $method='GET') {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_HEADER, 1);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_COOKIESESSION,true);
       $data = curl_exec($ch);
       curl_close($ch);
       return $data;
    }
}

?>