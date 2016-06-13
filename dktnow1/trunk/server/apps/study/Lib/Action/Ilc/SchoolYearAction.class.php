<?php
/**
 * SchoolYearAction
 * 学年时间管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class SchoolYearAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取配置参数
        $start = C('SCHOOL_YEAR_START');
        $end = C('SCHOOL_YEAR_END');

        // 计算学年
        $year = array();
        for ($i = $start; $i <= $end; $i ++) {
            $year[$i] = $i;
        }

        // 赋值
        $this->year = $year;
    }

    public function _filter(&$map) {

        $map['s_id'] = $this->authInfo['s_id'];
    }

    // 添加
    public function insert() {

        $_POST['s_id'] = $where['s_id'] = $this->authInfo['s_id'];
        $where['sy_year'] = $_POST['sy_year'];
        if (M('SchoolYear')->where($where)->getField('sy_id')) {
            $this->error('此学年已设置');
        }

        $_POST['a_id'] = $this->authInfo['a_id'];
        parent::insert();

    }

    // 更新
    public function update() {

        $term = getXq($this->authInfo['s_id']);

        if ($_POST['sy_year'] == $term['cc_year']) {

            // 上学期
            if ($term['cc_xq'] == 1) {
                $time = strtotime($_POST['sy_up_end']);

            // 下学期
            } else {
                $time = strtotime($_POST['sy_down_end']);
            }

            // 更新dkt_school表的s_next_graduate_time字段并清除学校缓存文件
            M('School')->where(array('s_id' => $this->authInfo['s_id']))->save(array('s_next_graduate_time' => $time));
            @unlink(DATA_PATH . '~school.php');

        }

        parent::update();
    }
}
?>