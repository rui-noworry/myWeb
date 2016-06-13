<?php
/**
 * SchoolModel
 * 学校管理
 *
 * 作者:  赵鹏 (zhaop@mink.com.cn)
 * 创建时间: 2013-5-02
 *
 */
class SchoolModel extends CommonModel {
	public function trueTableName(){
		return $this->trueTableName;
	}
    protected $_auto = array(
        array('s_created', 'time', 1, 'function'),
        array('s_updated', 'time', 2, 'function'),
    );
}
?>