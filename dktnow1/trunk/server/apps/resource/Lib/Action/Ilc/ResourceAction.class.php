<?php
/**
 * ResourceAction
 * 资源
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-6-5
 *
 */
class ResourceAction extends CommonAction{

    public function index() {
        $this->display();
    }

    public function add() {
        $this->maxSize = intval(ini_get('upload_max_filesize'));
        $this->display();
    }

    // 获取资源列表
    public function lists() {

        $result = D('Resource')->lists($this->authInfo['s_id'], 1);

        // 模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取栏目信息
        $rc_idArr = getValueByField($result['list'], 'rc_id');
        $category = M('ResourceCategory')->where(array('rc_id' => array('IN', $rc_idArr)))->select();
        $category = setArrayByField($category, 'rc_id');

        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['m_title'] = $model[$value['m_id']]['m_title'];

            if ($category[$value['rc_id']]['rc_title']) {
                $result['list'][$key]['rc_title'] = $category[$value['rc_id']]['rc_title'];
            } else {
                $result['list'][$key]['rc_title'] = '无';
            }

        }

        echo json_encode($result);
    }

    // 添加资源
    public function insert() {

        $data['a_id'] = $this->authInfo['a_id'];
        $data['re_created'] = time();
        $data['s_id'] = $this->authInfo['s_id'];

        // 用户上传一个资源加一分
        M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->setInc('a_points', count($_SESSION['result']));

        if ($_SESSION['result']) {

            $table = getResourceConfigInfo(1);

            // 读取配置文件，看看文档和视频是否需要自动转码
            $documentCode = C('AUTO_TRANS_DOCUMENT');
            $videoCode = C('AUTO_TRANS_VIDEO');

            // 添加多个资源
            foreach ($_SESSION['result'] as $key => $value) {

                $data['m_id'] = $value['m_id'];
                $data['re_title'] = $value['re_title'];
                $data['re_ext'] = $value['fileExt'];
                $data['re_savename'] = $value['savename'];

                if (in_array($value['m_id'], array(1,3))) {
                    $data['re_is_transform'] = 1;
                }

                $data['re_is_pass'] = 1;

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

        $table = getResourceConfigInfo(1);

        // 获取用户资源信息
        $authResource = M($table['TableName'])->where(array('re_id' => array('IN', $ids)))->select();

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
        $save = getResourceConfigInfo(1);

        $tmpType = 0;
        if (in_array($result['m_id'], array(1,3))) {
            $tmpType = 1;
        }

        $savePath = $save['Path'][$tmpType] . $result['fileType'] . '/';

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        $savePath .= $time . "/";

        if (!is_dir($savePath)) {
            @mkdir($savePath, 0755);
        }

        $allowType = C('ALLOW_FILE_TYPE');

        $result['savename'] = parent::upload($allowType[$fileType], $savePath, $thumb, '100,600', '75,450', '100/,600/');

        return $result;
    }

    //多文件上传
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

        $result = D('Resource')->managerPublish($this->authInfo['a_id'], $_POST['re_id'], $_POST['rc_id'], $_POST['re_points']);

        if ($result['status']) {
            $this->redirect('/Ilc/Resource/');
        } else {
            $this->error($result['message']);
        }
    }

    // 编辑
    public function edit() {

        // 接收参数
        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->error('请选择编辑项');
        }

        $data = M('Resource')->where(array('re_id' => $id))->find();

        // 获取所属栏目
        $data['rc_title'] = M('ResourceCategory')->where(array('rc_id' => $data['rc_id']))->getField('rc_title');

        // 学校信息
        $school = loadCache('school');

        $data['s_name'] = $school[$data['s_id']]['s_name'];

        // 用户信息
        $auth = getDataByArray('Auth', $data, 'a_id', 'a_id, a_nickname');
        $data['a_nickname'] = $auth[$data['a_id']]['a_nickname'];

        // 模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取文档路径
        $time = date(C('RESOURCE_SAVE_RULES'), $data['re_created']);
        $filePath = getResourceConfigInfo(1);

        if ($data['m_id'] == 1) {
            $data['filePath'] =
            $filePath['Path'][$data['re_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/600/'.$data['re_savename'];
        } else {
            $data['filePath'] = $filePath['Path'][$data['re_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'.$data['re_savename'];
        }

        $this->assign('data', $data);
        $this->display();
    }

    public function update() {

        if (!isset($_POST)) {
            $this->error('操作失败');
        }

        M('Resource')->save($_POST);

        $this->redirect('/Ilc/Resource/');
    }

    // 下载资源
    public function downLoad() {

        D('Resource')->download();
    }

    // 删除文件的物理路径
    public function _before_delete() {

        $ids = $_REQUEST['id'];

        if (!$ids) {
            $this->error('请选择要删除的选项');
        }

        // 模型信息
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        $resource = M('Resource')->where(array('re_id' => array('IN', $ids)))->select();
        $resource = setArrayByField($resource, 're_id');

        $filePath = getResourceConfigInfo(1);

        foreach (explode(',', $ids) as $key => $value) {

            $time = date(C('RESOURCE_SAVE_RULES'), $resource[$value]['re_created']);

            if ($resource[$value]['m_id'] == 1) {

                unlink($filePath['Path'][$resource[$value]['re_is_transform']].$model[$resource[$value]['m_id']]['m_name'].'/'.$time.'/600/'.$resource[$value]['re_savename']);

                unlink($filePath['Path'][$resource[$value]['re_is_transform']].$model[$resource[$value]['m_id']]['m_name'].'/'.$time.'/100/'.$resource[$value]['re_savename']);
            }

            unlink($filePath['Path'][$resource[$value]['re_is_transform']].$model[$resource[$value]['m_id']]['m_name'].'/'.$time.'/'.$resource[$value]['re_savename']);

        }

    }
}
?>