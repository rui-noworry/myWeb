<?php
/**
 * AuthResourceAction
 * 资源模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-3
 *
 */
class AuthResourceAction extends BaseAction{

    // 添加资源页面
    public function index() {
        $this->maxSize = intval(ini_get('upload_max_filesize'));
        $this->display();
    }

    // 添加资源
    public function insert() {

        $data['a_id'] = $this->authInfo['a_id'];
        $data['ar_created'] = time();
        $data['s_id'] = $this->authInfo['s_id'];

        // 用户上传一个资源加一分
        M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

        if ($_SESSION['result']) {

            // 读取配置文件，看看文档和视频是否需要自动转码
            $documentCode = C('AUTO_TRANS_DOCUMENT');
            $videoCode = C('AUTO_TRANS_VIDEO');

            // 添加多个资源
            foreach ($_SESSION['result'] as $key => $value) {

                $data['m_id'] = $value['m_id'];
                $data['ar_title'] = $value['re_title'];
                $data['ar_ext'] = $value['fileExt'];
                $data['ar_savename'] = $value['savename'];

                if (in_array($value['m_id'], array(1,3,5))) {
                    $data['ar_is_transform'] = 1;
                }

                $table = getResourceConfigInfo(0);
                $idss = M($table['TableName'])->add($data);
                $result[] = $idss;

                // 文档
                if ($data['m_id'] == 4) {
                    $doc[] = $idss;
                }

                // 视频
                if ($data['m_id'] == 2) {
                    $video[] = $idss;
                }

            }

            unset($_SESSION['result']);
            echo json_encode($result);

            // 如果为1且文上传附件中有文档，就立即转码
            if ($documentCode && $doc) {
                trans(0, $doc);
            }

            // 如果为1且文上传附件中有视频，就立即转码
            if ($videoCode && $video) {
                trans(0, $video);
            }

        } else {
            echo 0;
        }
    }

    // 资源发布页
    public function resourcePublish() {

        // 接收参数
        $ids = $_REQUEST['ids'];

        if (!$ids) {
            $this->error('参数错误');
        }

        $table = getResourceConfigInfo(0);

        // 获取用户资源信息
        $authResource = M($table['TableName'])->where(array('ar_id' => array('IN', $ids), 'a_id' => $this->authInfo['a_id']))->select();

        if (!$authResource) {
            $this->error('此数据不存在');
        }

        // 获取模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        foreach ($authResource as $key => $value) {
            $authResource[$key]['m_title'] = $model[$value['m_id']]['m_title'];
            $authResource[$key]['m_list'] = $model[$value['m_id']]['m_list'];
            $mList[] = $model[$value['m_id']]['m_list'];

        }

        $reCate = M('ResourceCategory')->where('rc_pid = 0 AND (s_id = 0 OR s_id = ' . $this->authInfo['s_id'] . ')')->select();

        foreach ($reCate as $rcValue) {
            if ($rcValue['s_id'] == 0) {
                $sys = 1;
            }

            if ($this->authInfo['s_id'] && $rcValue['s_id'] == $this->authInfo['s_id']) {
                $sch = $this->authInfo['s_id'];
            }
        }

        // 获取属性信息
        $attribute = M('Attribute')->where(array('at_id' => array('IN', implode(',', $mList)), 'at_status' => 1))->select();

        foreach ($attribute as $key => $value) {

            if ($value['at_type'] == 2) {

                $attribute[$key]['at_extra'] = explode(',', $value['at_extra']);
            }
        }

        $this->attribute = $attribute;

        $this->dis = intval($_REQUEST['dis']);
        $this->sys = intval($sys);
        $this->sch = intval($sch);
        $this->assign('authResource', $authResource);
        $this->display();
    }

    /*
     * 获取下级栏目信息
     * $id 栏目ID
     * $level 层级
     */
    public function findSub() {

        // 返回值
        $result = array();

        $id = intval($_POST['id']);
        $where = 'rc_pid = ' . $id . ' AND ( s_id = 0 OR s_id = ' . $this->authInfo['s_id'] . ')';

        // 获取栏目
        $resourceCategory = M('ResourceCategory')->where($where)->select();

        echo json_encode($resourceCategory);
    }

    // 资源上传
    public function upload($filename, $filesize){

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

        $model = reloadCache('model');

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

        if (in_array($result['m_id'], array(1,3,5))) {

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

    // 多文件上传
    public function uploadAttach(){

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

    // 发布
    public function publish() {

        $result = D('AuthResource')->publish($this->authInfo['a_id'], $_POST['ar_id'], $_POST['rc_id'], $_POST['re_points']);

        if ($result['status']) {
            $this->redirect('/MyResource');
        } else {
            $this->error($result['message']);
        }
    }

    // 下载
    public function download() {
        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->redirect('/MyResource');
        }

        // 验证
        $res = M('AuthResource')->where(array('ar_id' => $id, 'a_id' => $this->authInfo['a_id']))->find();

        if (!$res) {
            $this->redirect('/MyResource');
        }

        // 组织数据，准备下载
        $table = getResourceConfigInfo(0);
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        $path = $table['Path'][$res['ar_is_transform']] . $model[$res['m_id']]['m_name'] . '/' . date(C('RESOURCE_SAVE_RULES'), $res['ar_created']) . '/' . substr($res['ar_savename'], 0, strrpos($res['ar_savename'], '.')) . '.' . $res['ar_ext'];

        $fileName = $res['ar_title'];

        download($path, iconv("utf-8", "gb2312", $fileName), $res['ar_ext'], false);
    }

    // 编辑
    public function edit() {

        // 接收参数
        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->error('没有此资源');
        }

        $data = M('AuthResource')->find($id);

        // 模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取文档路径
        $time = date(C('RESOURCE_SAVE_RULES'), $data['ar_created']);
        $filePath = getResourceConfigInfo(0);

        if ($data['m_id'] == 1) {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/600/'.$data['ar_savename'];
        } elseif ($data['m_id'] == 4) {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'. getFileName($data['ar_savename'], 'swf');
        } else {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'.$data['ar_savename'];
        }

        $data['filePath'] = turnTpl($data['filePath']);

        $this->assign('data', $data);
        $this->display();
    }

    // 更新
    public function update() {

        if (!isset($_POST)) {
            $this->error('操作失败');
        }

        if (!M('AuthResource')->where(array('ar_id' => intval($_POST['ar_id']), 'a_id' => $this->authInfo['a_id']))->find()) {
            $this->error('非法操作');
        }

        M('AuthResource')->save($_POST);

        $this->redirect('/MyResource/');

    }

}
?>