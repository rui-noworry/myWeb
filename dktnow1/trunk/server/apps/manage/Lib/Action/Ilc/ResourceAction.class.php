<?php
/**
 * ResourceAction
 * 资源管理
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-14
 *
 */
class ResourceAction extends CommonAction{

    public function index() {

        // 搜索条件
        if (intval($_POST['rc_id'])) {
            $where['rc_id'] = intval($_POST['rc_id']);
            $this->assign('rc_id', intval($_POST['rc_id']));
        }

        if ($_POST['re_title']) {
            $where['re_title'] = array('LIKE', '%'.$_POST['re_title'].'%');
            $this->assign('re_title', $_POST['re_title']);
        }

        // 审核通过的
        $where['re_is_pass'] = 1;

        // 获取数据
        $result = getListByPage('Resource', 're_id DESC', $where, C('PAGE_SIZE'));

        // 获取栏目信息
        $rc_idArr = getValueByField($result['list'], 'rc_id');
        $category = M('ResourceCategory')->where(array('rc_id' => array('IN', $rc_idArr)))->select();
        $category = setArrayByField($category, 'rc_id');

        // 处理数据
        foreach ($result['list'] as $key => $value) {
            if ($category[$value['rc_id']]['rc_title']) {
                $result['list'][$key]['rc_title'] = $category[$value['rc_id']]['rc_title'];
            } else {
                $result['list'][$key]['rc_title'] = '无';
            }
        }

        // 资源推荐
        $recommend = C('RESOURCE_RECOMMEND_TYPE');

        $this->assign('recommend', $recommend);
        $this->assign('list', $result['list']);
        $this->assign('page', $result['page']);
        $this->display();
    }

    public function _before_add() {
        $this->maxSize = intval(ini_get('upload_max_filesize'));
    }

    // 添加资源
    public function insert() {

        $data['re_created'] = time();
        $data['rc_id'] = intval($_POST['rc_id']);

        if ($_SESSION['result']) {

            // 读取配置文件，看看文档和视频是否需要自动转码
            $documentCode = C('AUTO_TRANS_DOCUMENT');
            $videoCode = C('AUTO_TRANS_VIDEO');

            // 后台上传资源默认通过审核
            $data['re_is_pass'] = 1;

            // 添加多个资源
            foreach ($_SESSION['result'] as $key => $value) {

                $data['m_id'] = $value['m_id'];
                $data['re_title'] = $value['re_title'];
                $data['re_ext'] = $value['fileExt'];
                $data['re_savename'] = $value['savename'];

                if (in_array($value['m_id'], array(1,3,5))) {
                    $data['re_is_transform'] = 1;
                }

                $table = getResourceConfigInfo(1);
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

            $this->success('成功');

            // 如果为1且文上传附件中有文档，就立即转码
            if ($documentCode && $doc) {
                trans(0, $doc);
            }

            // 如果为1且文上传附件中有视频，就立即转码
            if ($videoCode && $video) {
                trans(0, $video);
            }

        } else {
            $this->error('请选择资源');
        }
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
        $where = 'rc_pid = ' . $id . ' AND s_id = 0';

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
        $save = getResourceConfigInfo(1);

        $savePath = $save['Path'][0] . $result['fileType'] . '/' . $time . "/";

        if (in_array($result['m_id'], array(1,3,5))) {

            // 如果是图片放在已转码的文件夹内
            $savePath = $save['Path'][1] . $result['fileType'] . '/' . $time . "/";
        }

        if(!is_dir($savePath)) {

            $isOk = mkdir($savePath, 0700, TRUE);

            if(!$isOk) {
                $this->error('没有权限创建上传目录');
            }
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

        // 模型信息
        $model = loadCache('model');
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

        // 资源推荐
        $recommend = C('RESOURCE_RECOMMEND_TYPE');

        // 属性
        $mList = $model[$data['m_id']]['m_list'];

        // 获取属性信息
        $attribute = M('Attribute')->where(array('at_id' => array('IN', $mList), 'at_status' => 1))->select();

        // 获取该资源已有的属性
        $attributed = M('AttributeRecord')->where(array('re_id' => $id))->select();

        $attributed = setArrayByField($attributed, 'are_name');

        foreach ($attribute as $key => $value) {

            if ($value['at_type'] == 2) {

                $attribute[$key]['at_extra'] = explode(',', $value['at_extra']);
            }

            $attribute[$key]['default'] = $attributed[$value['at_name']]['are_value'];

        }

        $this->attribute = $attribute;
        $this->assign('recommend', $recommend);
        $this->assign('data', $data);
        $this->display();
    }

    public function _before_update() {

       // 获取该资源已有的属性
       $attribute = M('AttributeRecord')->where(array('re_id' => intval($_POST['re_id'])))->select();

       $nameArr = getValueByField($attribute, 'are_name');

       if (is_array($_POST['text'])) {

           foreach ($_POST['text'] as $key => $value) {

               if ($value['are_value'] != '' && $value['are_name'] != '') {

                    $data['re_id'] = intval($_POST['re_id']);
                    $data['are_name'] = $value['are_name'];
                    $data['are_value'] = $value['are_value'];

                   if (in_array($value['are_name'], $nameArr)) {
                        M('AttributeRecord')->where(array('are_name' => $data['are_name'], 're_id' => $data['re_id']))->save($data);
                   } else {
                        M('AttributeRecord')->add($data);
                   }
               }
           }
       }

    }

    // 下载资源
    public function downLoad() {

        D('Resource')->download();
    }

    // 资源推荐
    public function resRecommend() {

        // 接收参数
        $ids = $_REQUEST['id'];
        $data['re_recommend'] = $_REQUEST['type'];
        $data['re_updated'] = time();

        if (!$ids) {
            $this->error('操作失败');
        }

        $res = M('Resource')->where(array('re_id' => array('IN', $ids)))->save($data);

        if ($res) {
            $this->success('成功');
        }

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