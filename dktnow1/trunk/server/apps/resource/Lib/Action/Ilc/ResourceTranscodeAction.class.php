<?php
/**
 * ResourceTranscodeAction
 * 资源转码
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-7
 *
 */
class ResourceTranscodeAction extends CommonAction {

    // 首页
    public function index() {

        // 读取配置转码配置文件
        $config = C('MP4_PARAM');
        $this->video = $config['video'];
        $this->voice = $config['voice'];
        $this->cover = $config['cover'];

        // 读取模板列表
        $where['s_id'] = $this->authInfo['s_id'];
        $where['a_id'] = $this->authInfo['a_id'];
        $this->template = M('ResourceTranscode')->where($where)->select();


        $this->display();
    }

    // 写入
    public function insert() {

        // 检测
        $this->checkInfo();

        // 组织数据
        $_POST['s_id'] = $where['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $where['a_id'] = $this->authInfo['a_id'];

        // 若增加启用的模板，并且已存在启用的模板，则设置当前的为启用，其他的都停用
        if (intval($_POST['flag'])) {
            $where['rt_status'] = 1;
            $data['rt_status'] = 0;
            M('ResourceTranscode')->where($where)->save($data);
        }

        $result = parent::insertData();
        if (!$result) {
            $this->error('模板保存失败');
        }
        $this->success($result);

    }

    // 更新
    public function update() {

        // 检测
        $this->checkInfo(2);

        // 组织数据
        $where['s_id'] = $this->authInfo['s_id'];
        $where['a_id'] = $this->authInfo['a_id'];

        // 若已存在启用的模板，则设置当前的为启用，其他的都停用
        if (intval($_POST['flag'])) {
            $where['rt_status'] = 1;
            $data['rt_status'] = 0;
            M('ResourceTranscode')->where($where)->save($data);
        }

        $result = parent::update();
        if (!$result) {
            $this->error('模板更新失败');
        }
        $this->success('模板更新成功');

    }

    // type为1是写入，2是更新
    public function checkInfo($type = 1) {

        // 在写入数据之前，还得判断下该用户添加的模板是否已超过最大数
        if ($type == 1) {
            $count = M('ResourceTranscode')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->count();
            if ($count && $count == 10) {
                $this->error('只允许添加十个模板');
            }
        }
    }

    // 删除
    public function delete() {

        $id = strval($_POST['id']);

        if (!$id) {
            $this->error('非法操作');
        }

        // 条件
        $where['s_id'] = $this->authInfo['s_id'];
        $where['a_id'] = $this->authInfo['a_id'];
        $where['rt_id'] = array('IN', $id);

        $result = M('ResourceTranscode')->where($where)->delete();
        if (!$result) {
            $this->error('模板删除失败');
        }
        $this->success('模板删除成功');

    }
}