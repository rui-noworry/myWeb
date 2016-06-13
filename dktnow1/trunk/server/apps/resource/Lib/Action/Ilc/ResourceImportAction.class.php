<?php
/**
 * ResourceImportAction
 * 资源导入
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-31
 *
 */
class ResourceImportAction extends CommonAction {

    public function index() {
        $this->display();
    }

    // 导入xls文件
    public function upload() {

        header("Content-Type:text/html; charset=utf-8");

        // 是否有提交
        if (!$_FILES['file']['size']) {
            $this->error('请选择导入文件提交');
        }

        // 目录是否存在
        $dir = strval($_POST['dir']);
        if ($dir == '') {
            $this->error('请填写目录');
        }
        if (!is_dir($dir)) {
            $this->error('该目录不存在');
        }

        if (strpos($dir, '\\\\') !== FALSE) {
            $dir = str_replace('\\\\\\\\', '/', $dir);
        }

        $fileName = parent::upload(explode ( ',', 'xls'), C('AUTH_IMPORT_PATH'));
        $fileName = C('AUTH_IMPORT_PATH') . $fileName;
        $this->check($fileName, $dir);
    }

    // 检测导入的xls文件
    public function check($fileName, $dir) {

        // 加载reader类
        import('@.ORG.Util.ExcelReader');

        $data = new ExcelReader();
        $data->setOutputEncoding('utf-8');

        // 读取文件
        $data->read($fileName);

        // 获取行数
        $numRows = $data->sheets[0]['numRows'];

        if ($numRows < 3) {
            $this->error('请导入正确的文件');
        }

        // 检测
        $arr = array();
        $count = 0;
        for($i=3; $i<=$numRows; $i++) {

            // 绕过空行
            if(!$data->sheets[0]['cells'][$i]) {
                continue;
            }

            // 文件名称
            $arr[$count]['re_title'] = $data->sheets[0]['cells'][$i][2];

            // 文件名
            $arr[$count]['re_savename'] = $data->sheets[0]['cells'][$i][3];
            if ($arr[$count]['re_savename'] == '') {
                $this->error('请检查第' . $i . '行文件名列，文件名不可为空');
            }
            if (strpos($arr[$count]['re_savename'], '.') === FALSE) {
                $this->error('请检查第' . $i . '行文件名列的是否正确');
            }
            if (!file_exists(iconv('utf-8','gbk', $dir . '/' .$arr[$count]['re_savename']))) {
                $this->error('请检查第' . $i . '行文件名列,该文件不存在');
            }

            // 如果文件名称为空，则截取文件名
            if ($arr[$count]['re_title'] == '') {
                $arr[$count]['re_title'] = str_replace(strstr($arr[$count]['re_savename'], '.'), '', $arr[$count]['re_savename']);
            }

            $arr[$count]['re_savename'] = $dir . '/' . $arr[$count]['re_savename'];

            // 标签名
            $arr[$count]['rta_id'] = $data->sheets[0]['cells'][$i][4];

            // 下载积分
            $arr[$count]['re_download_points'] = $data->sheets[0]['cells'][$i][5];

            if ($arr[$count]['re_download_points'] == '') {
                $this->error('请检查第' . $i . '行下载积分列，下载积分不可为空');
            }
            if (!preg_grep('/^\d+$/', array($arr[$count]['re_download_points']))) {
                $this->error('请检查第' . $i . '行下载积分列，下载积分需为数字');
            }

            $count++;
        }

        // 删除xls文件
        unlink($fileName);

        $this->import($arr);
    }

    // 批量导入
    public function import($data = array()) {

        // 如果用户刷新页面或是直接通过url过来的话，则进入批量导入页面
        if (!$data) {
            unset($_SESSION['resource']);
            $this->redirect('/Ilc/ResourceImport/index');
        }

        $array = array();
        $table = getResourceConfigInfo(1);
        foreach ($data as $key => $value) {
            $arr['id'] = $key;
            $arr['rta_id'] = $value['rta_id'];
            $tmp = $this->procResource($value['re_savename'], $table, FALSE);
            $arr['m_id']  = $tmp[0];
            $arr['re_savename'] = $value['re_savename'];
            $arr['re_filesize'] = $tmp[2];
            $arr['re_is_transform'] = $tmp[3];
            $arr['re_ext'] = pathinfo($value['re_savename'], PATHINFO_EXTENSION);
            $arr['re_title'] = str_replace('.' . $arr['re_ext'], '', $value['re_title']);
            $arr['re_download_points'] = $value['re_download_points'];
            $array[] = $arr;
        }

        // 用session存储
        $_SESSION['resource'] = $array;

        $this->array = $array;
        $this->display();
    }

    // 把用户填写的标签写入标签表，并返回相关的ID
    public function turnIdToWord($data) {

        if ($data == '') {
            return '';
        }

        // 存放标签id
        $rta_id = array();

        // 分割标签
        $tagName = explode(' ', $data);
        $where['rta_title'] = array('IN', $tagName);

        $tag = M('ResourceTag');
        $res= setArrayByField($tag->where($where)->field('rta_id,rta_title')->select(), 'rta_title');

        // 如果资源标签表内有此标签，就在其相应的点击字段上加1，否则就写入数据库
        if ($res) {
            $rta_id = getValueByField($res, 'rta_id');
            $save['rta_updated'] = time();
            $save['rta_count'] = array('exp', 'rta_count+1');
            $tag->where(array('rta_id' => array('IN', $rta_id)))->save($save);
        }

        foreach ($tagName as $k => $v) {
            if (!array_key_exists($v, $res)) {
                $add['rta_title'] = $v;
                $add['rta_count'] = 1;
                $add['rta_created'] = time();
                array_push($rta_id, $tag->add($add));
            }
        }

        return ',' . implode(',', $rta_id) . ',';
    }

    // 依据资源类型处理导入的资源
    public function procResource($resource, $table, $flag = FALSE) {

        // 载入类型
        $allowType = C('ALLOW_FILE_TYPE');

        // 依据模型不同选择不同的目录
        $model = loadCache('model');
        $ext = pathinfo($resource, PATHINFO_EXTENSION);
        foreach ($allowType as $k => $v) {
            if (in_array($ext, $v)) {
                $name = $k;
            }
        }

        $file = iconv('utf-8', 'gbk', $resource);

        $mid = $model[$name]['m_id'];
        if(in_array($mid, array(1, 3))) {
            $to = $table['Path'][1] . $name . '/';
            $tran = 1;
        } else {

            // 如果资源是视频，且是已经转过码的
            if ($mid == 2 && strtolower(pathinfo($resource, PATHINFO_EXTENSION)) == 'mp4') {
                $to = $table['Path'][1] . $name . '/';
                $tran = 1;

               // 生成快照
               videoToMp4($file, array('rt_cover_rp' => '100x75'), $to . date(C('RESOURCE_SAVE_RULES'), time()) . '/', TRUE);
            } else {
                $to = $table['Path'][0] . $name . '/';
                $tran = 0;
            }
        }

        if ($flag == FALSE) {
            $filesize = round(filesize($file)/1024/1024, 2) . 'MB';
        }

        // 如果传过来的falg参数为TRUE，则说明是移动
        if ($flag == TRUE) {

            // 如果没有此目录便创建
            $to .= date(C('RESOURCE_SAVE_RULES'), time()) . '/';

            if (!is_dir($to)) {
               mk_dir($to);
            }

            // 图片还需生成缩略图
            if ($mid == 1) {

                // 是图像文件生成缩略图
                $thumbWidth = explode(',', '100,600');
                $thumbHeight = explode(',','75,450');
                $thumbPrefix = explode(',', '100/,600/');
                $thumbPath = $to;
                $savename = uniqid() . '.' . pathinfo($file, PATHINFO_EXTENSION);

                // 生成图像缩略图
                import("@.ORG.Util.Image");
                for($i=0, $len=count($thumbWidth); $i<$len; $i++) {
                    $thumbname  =  $thumbPath . $thumbPrefix[$i] . '/' . $savename;
                    if(!is_dir(dirname($thumbname))) {
                        mkdir(dirname($thumbname));
                    }
                    Image::thumb($file, $thumbname, '', $thumbWidth[$i], $thumbHeight[$i], true);
                }
            }

            // 移动文件
            $to .= basename($file);
            copy($file, $to);
            $savename = uniqid() . '.' . pathinfo($to, PATHINFO_EXTENSION);
            $toName = dirname($to) . '/' . $savename;
            rename($to, $toName);
            unlink($file);

            $filesize = round(filesize($to)/1024/1024, 2) . 'MB';
        }

        return array($mid, $savename, $filesize, $tran);
    }

    // 批量导入
    public function insertResource() {

        // 检测参数
        $rc_id = intval($_POST['rc_id']);
        $id = strval($_POST['idss']);
        if (!$rc_id || !$id) {
            $this->error('非法操作');
        }

        $id = explode(',', trim($id, ','));
        $arr['a_id'] = $this->authInfo['a_id'];
        $arr['s_id'] = $this->authInfo['s_id'];
        $table = getResourceConfigInfo(1);

        // 读取配置文件，看看文档和视频是否需要自动转码
        $documentCode = C('AUTO_TRANS_DOCUMENT');
        $videoCode = C('AUTO_TRANS_VIDEO');

        // 循环导入
        foreach ($id as $k => $v) {
            $value = $_SESSION['resource'][$v];
            $arr['rta_id'] = $this->turnIdToWord($value['rta_id']);
            $tmp = $this->procResource($value['re_savename'], $table, TRUE);
            $arr['m_id']  = $value['m_id'];
            $arr['re_savename'] = $tmp[1];
            $arr['re_is_transform'] = $value['re_is_transform'];
            $arr['re_ext'] = $value['re_ext'];
            $arr['re_title'] = $value['re_title'];
            $arr['re_is_pass'] = 1;
            $arr['rc_id'] = $rc_id;
            $arr['re_download_points'] = $value['re_download_points'];
            $arr['re_created'] = time();
            $idss = M($table['TableName'])->add($arr);

            // 文档
            if ($value['m_id'] == 4) {
                $doc[] = $idss;
            }

            // 视频
            if ($value['m_id'] == 2 && $value['re_is_transform'] == 0) {
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

        $this->success('导入成功');
    }
}