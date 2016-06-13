<?php
/**
 * RoleMode
 * 角色模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-03
 *
 */

class RoleModel extends CommonModel {
    protected $_auto = array(
        array('r_created', 'time', 'function', self::MODEL_INSERT),
        array('r_updated', 'time', 'function', self::MODEL_UPDATE),
    );

    // 获取当前组的操作权限列表
    public function getGroupAppList($groupId) {

        $rs = $this->db->query('SELECT b.n_id, b.n_title, b.n_name FROM ' . $this->tablePrefix . 'access AS a ,' . $this->tablePrefix . 'node as b WHERE a.n_id = b.n_id AND b.n_pid = 0 and a.r_id = ' . $groupId);

        return $rs;
    }

    // 当前项目的授权模块信息
    public function getGroupModuleList($groupId, $appId) {

        $table = $this->tablePrefix . 'access';

        $rs = $this->db->query('SELECT b.n_id, b.n_title, b.n_name FROM ' . $this->tablePrefix . 'access AS a ,' . $this->tablePrefix . 'node AS b WHERE a.n_id = b.n_id AND b.n_pid = ' . $appId . ' AND a.r_id = ' . $groupId);

        return $rs;
    }

    // 删除应用授权
    public function delGroupApp($groupId) {

        $table = $this->tablePrefix . 'access';
        $result = $this->db->execute('DELETE FROM '.$table.' WHERE n_level=1 AND r_id=' . $groupId);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 保存应用授权
    public function setGroupApps($groupId, $appIdList) {

        if (empty($appIdList)) {
            return true;
        }

        $id = implode(',', $appIdList);

        $where = 'a.r_id = ' . $groupId . ' AND b.n_id IN(' . $id . ')';

        $result = $this->db->execute('INSERT INTO ' . $this->tablePrefix . 'access (r_id,n_id,n_pid,n_level) SELECT a.r_id, b.n_id,b.n_pid,b.n_level FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'node b WHERE '.$where);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 删除模块授权
    function delGroupModule($groupId, $appId) {

        $table = $this->tablePrefix . 'access';

        $result = $this->db->execute('DELETE FROM ' . $table . ' WHERE n_level=2 AND n_pid=' . $appId . ' AND r_id=' . $groupId);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 保存模块授权
    function setGroupModules($groupId, $moduleIdList) {

        if(empty($moduleIdList)) {
            return true;
        }

        if(is_array($moduleIdList)) {
            $moduleIdList = implode(',', $moduleIdList);
        }

        $where = 'a.r_id =' . $groupId . ' AND b.n_id IN('.$moduleIdList.')';
        $rs = $this->db->execute('INSERT INTO ' . $this->tablePrefix . 'access (r_id,n_id,n_pid,n_level) SELECT a.r_id, b.n_id,b.n_pid,b.n_level FROM ' . $this->tablePrefix.'role a, ' . $this->tablePrefix . 'node b WHERE ' . $where);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 获取当前组的操作权限列表
    function getGroupActionList($groupId, $moduleId) {

        $table = $this->tablePrefix . 'access';
        $rs = $this->db->query('SELECT b.n_id,b.n_title,b.n_name FROM ' . $table . ' AS a ,' . $this->tablePrefix . 'node AS b WHERE a.n_id=b.n_id AND b.n_pid=' . $moduleId . ' AND a.r_id=' . $groupId);

        return $rs;
    }

    // 获取当前组的用户列表
    function getGroupUserList($groupId) {

        $table = $this->tablePrefix . 'role_user';

        $rs = $this->db->query('SELECT b.u_id,b.u_nickname FROM '.$table.' AS a ,' . $this->tablePrefix . 'user AS b WHERE a.u_id=b.u_id AND  a.r_id=' . $groupId);

        return $rs;
    }

    // 删除组用户
    function delGroupUser($groupId) {

        $table = $this->tablePrefix . 'role_user';

        $result = $this->db->execute('DELETE FROM ' . $table . ' WHERE r_id=' . $groupId);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 保存用户列表
    function setGroupUsers($groupId, $userIdList) {

        if(empty($userIdList)) {
            return true;
        }

        if(is_string($userIdList)) {
            $userIdList = explode(',', $userIdList);
        }

        array_walk($userIdList, array($this, 'fieldFormat'));
        $userIdList = implode(',', $userIdList);

        $where = 'a.r_id =' . $groupId . ' AND b.u_id IN(' . $userIdList . ')';

        $rs = $this->execute('INSERT INTO ' . $this->tablePrefix . 'role_user (r_id,u_id) SELECT a.r_id, b.u_id FROM ' . $this->tablePrefix.'role a, ' . $this->tablePrefix . 'user b WHERE ' . $where);
        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    protected function fieldFormat(&$value) {

        if (is_int($value)) {

            $value = intval($value);
        } else if (is_float($value)) {

            $value = floatval($value);
        } else if (is_string($value)) {

            $value = '"' . addslashes($value) . '"';
        }

        return $value;
    }

    // 删除操作授权

    function delGroupAction($groupId, $moduleId) {

        $table = $this->tablePrefix . 'access';

        $result = $this->db->execute('DELETE FROM ' . $table . ' WHERE n_level=3 AND n_pid=' . $moduleId . ' AND r_id=' . $groupId);

        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }

    // 保存操作授权
    function setGroupActions($groupId, $actionIdList) {

        if (empty($actionIdList)) {
            return true;
        }
        if (is_array($actionIdList)) {
            $actionIdList = implode(',', $actionIdList);
        }
        $where = 'a.r_id =' . $groupId . ' AND b.n_id IN(' . $actionIdList . ')';
        $rs = $this->db->execute('INSERT INTO ' . $this->tablePrefix . 'access (r_id,n_id,n_pid,n_level) SELECT a.r_id, b.n_id,b.n_pid,b.n_level FROM ' . $this->tablePrefix . 'role a, ' . $this->tablePrefix . 'node b WHERE ' . $where);
        if ($result===false) {
            return false;
        } else {
            return true;
        }
    }
}
?>