<?php
/**
 * ModelAction
 * 模型管理
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-5
 *
 */
class ModelAction extends CommonAction {

    protected $attrList = array();

    // 初始化
    public function _initialize() {
        parent::_initialize();

        if (empty($this->attrList)) {
            $this->attrList = M('Attribute')->select();
        }
    }

    // 模型列表
    public function index() {

        // 查询条件
        if ($_REQUEST['m_name']) {
            $where['m_name'] = array('LIKE', "%".$_REQUEST['m_name']."%");
        }

        // 分页获取数据
        $model = getListByPage('Model', 'm_id ASC', $where);

        foreach ($model['list'] as $mValue) {
            $mList[$mValue['m_id']] = $mValue['m_list'];
        }
        // 获取属性信息
        $attribute = M('Attribute')->where(array('at_id' => array('IN', implode(',', $mList)), 'at_status' => 1))->select();
        $attribute = setArrayByField($attribute, 'at_id');

        foreach ($mList as $key => $value) {
            if ($value) {
                $tmp = explode(',', $value);

                $mList[$key] = array();
                foreach ($tmp as $tKey => $tValue) {
                    $mList[$key][$tKey] = $attribute[$tValue]['at_title'];
                }
            }
        }

        // 处理数据
        foreach ($model['list'] as $nKey => $nValue) {
            $model['list'][$nKey]['m_list'] = implode(',', $mList[$nValue['m_id']]);
        }

        $this->assign('list', $model['list']);
        $this->assign('page', $model['page']);
        $this->display();
    }

    //模型的添加操作
    public function _before_add(){
        $this->assign('attrList', $this->attrList);
    }

    //模型管理的写入操作
    public function insert(){
        $_POST['m_list'] = $_POST['m_list'] ? implode(',', $_POST['m_list']) : '';
        parent::insert();
    }

    //模型管理的编辑操作
    public function _before_edit(){
        $this->assign('attrList', $this->attrList);
    }

    //模型管理的更新操作
    public function update(){
        $_POST['m_list'] = implode(',' ,$_POST['m_list']);
        parent::update();
    }

    // 缓存
    public function cache($model) {

        // 字段类型，对应attribute的at_type
        $type = array(
            1 => 'varchar(255) DEFAULT NULL',
            2 => 'varchar(255) DEFAULT NULL',
        );

        $prefix = C('DB_PREFIX');
        $engine = C('CACHE_TABLE_ENGINE') ? C('CACHE_TABLE_ENGINE') : 'MyISAM';

        // 删除字段缓存文件
        unlink(DATA_PATH.'_fields/Cache'.ucfirst($model['m_name']).'.php');

        $sql = 'DROP TABLE IF EXISTS `'.$prefix.'cache_'.$model['m_name'].'`;';
        M()->query($sql);

        $sql = 'CREATE TABLE `' . $prefix . 'cache_' . $model['m_name'] . '` (
                  `re_id` int(10) unsigned NOT NULL ,
                  `a_id` int(10) unsigned NOT NULL COMMENT "创建者ID",
                  `re_title` varchar(255) NOT NULL COMMENT "资源名称",
                  `re_subject` tinyint(3) unsigned NOT NULL COMMENT "所属学科",
                  `re_version` tinyint(3) unsigned NOT NULL COMMENT "版本",
                  `re_school_type` tinyint(3) unsigned NOT NULL COMMENT "学制",
                  `re_grade` tinyint(3) unsigned NOT NULL COMMENT "年级",
                  `re_semester` tinyint(3) unsigned NOT NULL COMMENT "学期",
                  `re_description` varchar(1024) default NULL COMMENT "资源描述",
                  `re_type` smallint(3) NOT NULL COMMENT "资源类型",
                  `re_ext` char(5) NOT NULL COMMENT "资源扩展名",
                  `re_permission` tinyint(3) unsigned NOT NULL COMMENT "资源开放权限",
                  `re_savepath` varchar(100) NOT NULL COMMENT "资源保存路径",
                  `re_recommend` tinyint(3) unsigned default "9" COMMENT "是否推荐",
                  `re_is_transform` tinyint(4) unsigned default "0" COMMENT "是否处理过0:未处理1:已处理",
                  `re_hits` int(10) unsigned NOT NULL default "0" COMMENT "浏览次数",
                  `re_downloads` int(10) unsigned NOT NULL default "0" COMMENT "下载次数",
                  `re_created` int(10) unsigned NOT NULL COMMENT "创建时间",
                  `re_updated` int(10) unsigned default "0" COMMENT "更新时间",
                  `re_deleted` int(10) unsigned default "0" COMMENT "删除时间",
                  `re_status` tinyint(3) unsigned default "9" COMMENT "审核状态9：是1：否",';

        if ($model['m_list']) {

            // 获取属性列表
            $list = setArrayByField($this->attrList, 'at_id');

            // 获取本模型的属性
            $attributeList = explode(',', $model['m_list']);
            foreach ($attributeList as $value) {
                $sql .= '`' . $list[$value]['at_name'] . '` ' . ($type[$list[$value]['at_type']] ? $type[$list[$value]['at_type']] : 'varchar(255) DEFAULT NULL') . ',';
            }
        }

        $sql .= '
            PRIMARY KEY  (`re_id`),
            KEY `re_type` (`re_type`),
            KEY `re_status` (`re_status`),
            KEY `re_is_transform` (`re_is_transform`)
            ) ENGINE=' . $engine . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

        M()->query($sql);
    }

    // 生成缓存表
    public function createCache(){

        $id = strval($_REQUEST['id']);

        if (!$id) {
            $this->error('请选择要生成缓存表的类型');
        }

        $where['m_id'] = array('IN', $id);
        $where['m_status'] = 1;

        $lists = M('Model')->where($where)->select();

        foreach ($lists as $key => $value) {
            $this->cache($value);
        }

        $this->success('模型表缓存成功！');
    }

    // 同步数据
    public function sync() {

        // 记录时间
        G('1');

        // 接收参数
        $id = strval($_REQUEST['id']);
        if (!$id) {
            $this->error('请选择要同步数据的类型');
        }

        // 类型条件
        $where['m_id'] = array('IN', $id);
        $where['m_status'] = 1;

        // 获取要同步的类型信息
        $modelRes = M('Model')->where($where)->select();

        // 清空要同步的类型的缓存表
        $models = array();
        foreach ($modelRes as $value) {
            $models[$value['m_id']] = $value;
            M('Cache' . ucfirst($value['m_name']))->where(1)->delete();
        }

        // 资源数据条件
        $reWhere['re_type'] = array('IN', $id);
        $reWhere['re_status'] = 9;

        // 要同步的数据总条数
        $count = M('Resource')->where($reWhere)->count();

        // 每次同步条数
        $maxRow = 1000;

        // 同步次数
        $times = ceil($count/$maxRow);

        // 数据同步是否完成
        if (empty($_GET['t'])) {
            $_GET['t'] = 1;
        } elseif( $_GET['t'] > $times) {
            $this->success('数据同步完成', __URL__);
        }

        $Resource = D('Resource');
        $list = $Resource->where($reWhere)->page($_GET['t'].','.$maxRow)->select();
        $result = $Resource->syncList($list, $models, true);

        $url = __URL__ . '/sync/?id=' . $id . '&t=' . ($_GET['t'] + 1);
        $this->success('数据同步进行中~完成' . $_GET['t'] . '/' . $times . '！耗时：' . G('1','2') . 's', $url);
    }
}
?>