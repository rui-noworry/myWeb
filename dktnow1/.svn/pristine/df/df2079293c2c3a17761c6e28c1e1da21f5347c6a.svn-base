<?php
/**
 * RoleAction
 * 后台角色
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-03
 *
 */
class RoleAction extends CommonAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();

        //创建数据对象
        $Role = D('Role');

        //查找满足条件的列表数据
        $roleList = $Role->getField('r_id,r_name');
        $this->assign('roleList', $roleList);
    }

    // 应用授权
    public function app() {

        // 读取系统的项目列表
        $node = D("Node");
        $appList = $node->where(array('n_level' => 1))->getField('n_id,n_title');

        // 获取当前用户组项目权限信息
        $groupId = isset($_GET['groupId'])? intval($_GET['groupId']): '';

        $groupAppList = array();

        if(!empty($groupId)) {

            $this->assign("selectGroupId", $groupId);

            // 获取当前组的操作权限列表
            $list = D('Role')->getGroupAppList($groupId);
            foreach ($list as $vo){
                $groupAppList[$vo['n_id']] = $vo['n_id'];
            }
        }
        $this->assign('groupAppList', $groupAppList);
        $this->assign('appList', $appList);
        $this->display();
    }

    // 保存应用授权
    public function setApp() {

        $id = $_POST['groupAppId'];
        $groupId = intval($_POST['groupId']);
        $Role = D('Role');

        $Role->delGroupApp($groupId);

        $result = $Role->setGroupApps($groupId, $id);

        if ($result===false) {
            $this->error('应用授权失败！');
        } else {
            $this->success('应用授权成功！');
        }
    }

    // 模块授权
    public function module() {

        $groupId = intval($_GET['groupId']);
        $appId = intval($_GET['appId']);

        if(!empty($groupId)) {

            $this->assign("selectGroupId",$groupId);

            //读取系统组的授权项目列表
            $list = D('Role')->getGroupAppList($groupId);

            foreach ($list as $vo){
                $appList[$vo['n_id']] = $vo['n_title'];
            }
            $this->assign("appList", $appList);
        }

        if(!empty($appId)) {

            $this->assign("selectAppId",$appId);
            //读取当前项目的模块列表
            $moduleList = D("Node")->where(array('n_level' => 2, 'n_pid=' => $appId))->getField('n_id,n_title');

            $this->assign('moduleList', $moduleList);
        }

        //获取当前项目的授权模块信息
        $groupModuleList = array();
        if(!empty($groupId) && !empty($appId)) {
            $list = D('Role')->getGroupModuleList($groupId, $appId);
            foreach ($list as $vo){
                $groupModuleList[$vo['n_id']] = $vo['n_id'];
            }
        }

        $this->assign('groupModuleList', $groupModuleList);
        $this->display();

    }

    // 保存模块授权
    public function setModule() {

        // 接收参数
        $id = $_POST['groupModuleId'];
        $groupId = $_POST['groupId'];
        $appId = $_POST['appId'];

        $group = D("Role");

        // 删除模块授权
        $group->delGroupModule($groupId, $appId);

        // 保存模块授权
        $result = $group->setGroupModules($groupId, $id);

        if($result===false) {
            $this->error('模块授权失败！');
        }else {
            $this->success('模块授权成功！');
        }
    }

    // 操作授权
    public function action() {

        // 接收参数
        $groupId = $_GET['groupId'];
        $appId  = $_GET['appId'];
        $moduleId = $_GET['moduleId'];

        $role = D('Role');

        if(!empty($groupId)) {

            $this->assign("selectGroupId",$groupId);

            // 读取系统组的授权项目列表
            $list = $role->getGroupAppList($groupId);

            foreach ($list as $vo) {
                $appList[$vo['n_id']] = $vo['n_title'];
            }

            $this->assign("appList",$appList);
        }

        if(!empty($appId)) {

            $this->assign("selectAppId",$appId);

            //读取当前项目的授权模块列表
            $list = $role->getGroupModuleList($groupId, $appId);

            foreach ($list as $vo) {
                $moduleList[$vo['n_id']] = $vo['n_title'];
            }

            $this->assign("moduleList", $moduleList);
        }

        $node = D("Node");

        if(!empty($moduleId)) {

            $this->assign("selectModuleId",$moduleId);

            //读取当前项目的操作列表
            $actionList = $node->where(array('n_level' => 3, 'pid' => $moduleId))->getField('n_id,n_title');
        }


        //获取当前用户组操作权限信息
        $groupActionList = array();

        if(!empty($groupId) && !empty($moduleId)) {

            //获取当前组的操作权限列表
            $list = $role->getGroupActionList($groupId, $moduleId);

            if($list) {
                foreach ($list as $vo){
                    $groupActionList[$vo['n_id']] = $vo['n_id'];
                }
            }
        }

        $this->assign('groupActionList', $groupActionList);
        $this->assign('actionList', $actionList);

        $this->display();
    }

    // 用户列表
    public function user() {

        // 读取系统的用户列表
        $userList = M("User")->where(array('u_id' => array('neq', 1)))->getField('u_id,u_nickname');

        $role = D("Role");

        // 获取当前用户组信息
        $groupId = isset($_GET['id'])? intval($_GET['id']): '';

        $groupUserList = array();

        if(!empty($groupId)) {

            $this->assign("selectGroupId", $groupId);

            // 获取当前组的用户列表
            $list = $role->getGroupUserList($groupId);

            foreach ($list as $vo){
                $groupUserList[$vo['u_id']] = $vo['u_id'];
            }
        }

        $this->assign('groupUserList', $groupUserList);
        $this->assign('userList', $userList);
        $this->display();
    }

    // 保存用户列表
    public function setUser() {

        $id = $_POST['groupUserId'];
        $groupId = $_POST['groupId'];

        $role = D("Role");

        // 删除组用户
        $role->delGroupUser($groupId);

        // 保存用户列表
        $result = $role->setGroupUsers($groupId, $id);

        if($result===false) {
            $this->error('授权失败！');
        }else {
            $this->success('授权成功！');
        }
    }

    // 保存操作授权
    public function setAction() {

        $id = $_POST['groupActionId'];

        $groupId = $_POST['groupId'];
        $moduleId = $_POST['moduleId'];

        $role = D("Role");

        $role->delGroupAction($groupId, $moduleId);
        $result = $role->setGroupActions($groupId, $id);

        if($result===false) {
            $this->error('操作授权失败！');
        }else {
            $this->success('操作授权成功！');
        }
    }
}
?>