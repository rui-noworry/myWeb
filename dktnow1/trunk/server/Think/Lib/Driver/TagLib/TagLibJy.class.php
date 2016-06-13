<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();
class TagLibJy extends TagLib {
    // 标签定义
    protected $tags   =  array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'query'=>array('attr'=>'sql,result','close'=>0),
        'tag'=>array('attr'=>'id,key,module,limit,result'),
        'cate'=>array('attr'=>'id,name,limit,pid,result','level'=>3),
        'article'=>array('attr'=>'id,name,cate,pcate,pos,type,limit,where,order,field','level'=>3),
        'data'=>array('attr'=>'name,field,limit,order,where,table,result,gc','level'=>2),
        'datalist'=>array('attr'=>'name,field,limit,order,where,table,result,key,mod,gc','level'=>3),
        'value'=>array('attr'=>'name,table,where,type,field','alias'=>'max,min,avg,sum,count','close'=>0),
        'call'=>array('attr'=>'name,id,result'),
        );

    // sql查询
    public function _query($attr,$content) {
        $tag    = $this->parseXmlAttr($attr,'query');
        $sql    = $tag['sql'];
        $result =  !empty($tag['result'])?$tag['result']:'result';
        $parseStr  = '<?php $'.$result.' = M()->query("'.$sql.'");';
        $parseStr .= 'if($'.$result.'):?>'.$content;
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    // 获取字段值 包括统计数据
    // type 包括 getField count max min avg sum
    public function _value($attr,$content,$type='getField'){
        $tag        =    $this->parseXmlAttr($attr,'value');
        $name    =    !empty($tag['name'])?$tag['name']:'Article';
        $type = !empty($tag['type'])?$tag['type']:$type;
        $filter  =  !empty($tag['filter'])?$tag['filter']:'';
        $parseStr   =  '<?php echo '.$filter.'(M("'.$name.'")';
        if(!empty($tag['table'])) {
            $parseStr .= '->table("'.$tag['table'].'")';
        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        $parseStr .= '->'.$type.'("'.$tag['field'].'"));?>';
        return $parseStr;
    }

    public function _count($attr,$content){
        return $this->_value($attr,$content,'count');
    }
    public function _sum($attr,$content){
        return $this->_value($attr,$content,'sum');
    }
    public function _max($attr,$content){
        return $this->_value($attr,$content,'max');
    }
    public function _min($attr,$content){
        return $this->_value($attr,$content,'min');
    }
    public function _avg($attr,$content){
        return $this->_value($attr,$content,'avg');
    }

    public function _data($attr,$content){
        $tag        =    $this->parseXmlAttr($attr,'data');
        $name    =    !empty($tag['name'])?$tag['name']:'Article';
        $result      =  !empty($tag['result'])?$tag['result']:'vo';
        if(!empty($tag['table'])) {
            $parseStr   =  '<?php $'.$result.' =M()';
        }else{
            $parseStr   =  '<?php $'.$result.' =M("Cache'.ucfirst($name).'")';
        }
        if(!empty($tag['table'])) {
            $parseStr .= '->table("'.$tag['table'].'")';
        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        if(!empty($tag['order'])){
            $parseStr .= '->order("'.$tag['order'].'")';
        }
        if(!empty($tag['limit'])){
            $parseStr .= '->limit("'.$tag['limit'].'")';
        }
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }
        $parseStr .= '->find();if($'.$result.'):$id=$'.$result.'["id"];?>'.$content;
        if(!empty($tag['gc'])) {
            $parseStr .= '<?php unset($'.$result.');?>';
        }
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    public function _datalist($attr,$content)
    {
        $tag        =    $this->parseXmlAttr($attr,'datalist');
        $name    =    !empty($tag['name'])?$tag['name']:'Article';
        $result      =  !empty($tag['result'])?$tag['result']:'vo';
        $key     =   !empty($tag['key'])?$tag['key']:'i';
        $mod    =   isset($tag['mod'])?$tag['mod']:'2';
        if(!empty($tag['table'])) {
            $parseStr   =  '<?php $_result =M()';
        }elseif('ALL'== strtoupper($name)){
            $parseStr   =  '<?php $_result =M("Article")';
        }else{
            $parseStr   =  '<?php $_result =M("Cache'.ucfirst($name).'")';
        }
        if(!empty($tag['table'])) {
            $parseStr .= '->table("'.$tag['table'].'")';
        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        if(!empty($tag['order'])){
            $parseStr .= '->order("'.$tag['order'].'")';
        }
        if(!empty($tag['limit'])){
            $parseStr .= '->limit("'.$tag['limit'].'")';
        }
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }
        $parseStr .= '->select();if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
        if('ALL'== strtoupper($name)) {
            $parseStr .= '$'.$result.'=D("Article")->getArticle($'.$result.'["id"],true,$'.$result.');';
        }
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );';
        $parseStr .=  'if($'.$result.'):$id=$'.$result.'["id"];if($'.$result.'["map_id"]) $'.$result.'["cur_id"]=$'.$result.'["id"];$'.$result.'["map_id"] =$'.$result.'["map_id"]?$'.$result.'["map_id"]:$'.$result.'["id"];$'.$result.'["id"]=$'.$result.'["map_id"];?>'.$this->parseTag($content);
        if(!empty($tag['gc'])) {
            $parseStr .= '<?php unset($'.$result.');?>';
        }
        $parseStr .= '<?php endif; endforeach; endif;?>';
        return $parseStr;
    }

    // 调用模型类的方法
    public function _call($attr,$content){
        $tag        =    $this->parseXmlAttr($attr,'call');
        $name    =    !empty($tag['name'])?$tag['name']:'Article';
        $result      =  !empty($tag['result'])?$tag['result']:'data';
        $method = $tag['method'];
        $vars = !empty($tag['vars'])?$tag['vars']:'';
        $parseStr   =  '<?php parse_str("'.$vars.'",$_var_);$'.$result.' =D("'.$name.'")';
        $parseStr   .= '->'.$method.'($_var_);';
        $parseStr .=  'if($'.$result.'):?>'.$content;
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    // 获取分类信息
    public function _cate($attr,$content){
        $tag        =    $this->parseXmlAttr($attr,'cate');
        $result      =  !empty($tag['result'])?$tag['result']:'cate';
        if(!empty($tag['id'])) {
            // 获取单个分类
            $parseStr   =  '<?php $'.$result.' = M("Cate")->find('.$tag['id'].');';
            $parseStr .=  'if($'.$result.'):?>'.$content;
        }elseif(!empty($tag['name'])) {
            // 获取单个分类
            $parseStr   =  '<?php $'.$result.' = M("Cate")->getByName('.$tag['name'].');';
            $parseStr .=  'if($'.$result.'):?>'.$content;
        }elseif(!empty($tag['pid'])){
            $key     =   !empty($tag['key'])?$tag['key']:'i';
            $mod    =   isset($tag['mod'])?$tag['mod']:'2';
            $parseStr   =  '<?php $_result = M("Cate")->order("sort")->where("is_show=1 AND status=1 AND pid='.$tag['pid'].'")->limit('.$tag['limit'].')->select();';
            $parseStr  .=  'if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
            $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );';
            $parseStr .=  'if($'.$result.'):?>'.$content.'<?php endif; endforeach;?>';
        }
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    // 获取热门标签
    public function _tag($attr,$content){
        $tag        =    $this->parseXmlAttr($attr,'tag');
        $result      =  !empty($tag['result'])?$tag['result']:'tag';
        $key     =   !empty($tag['key'])?$tag['key']:'i';
        $mod    =   isset($tag['mod'])?$tag['mod']:'2';
        $parseStr   =  '<?php $_result = M("Tag")->order("count desc")';
        if(!empty($tag['module'])){
            $parseStr .= '->where("module=\''.$tag['module'].'\'")';
        }
        if(!empty($tag['limit'])){
            $parseStr .= '->limit("'.$tag['limit'].'")';
        }
        $parseStr .= '->select();if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );';
        $parseStr .=  'if($'.$result.'):?>'.$content.'<?php endif; endforeach;?>';
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    public function _article($attr,$content){
        $tag        =    $this->parseXmlAttr($attr,'article');
        $result      =  !empty($tag['result'])?$tag['result']:'article';
        $name    =    !empty($tag['name'])?$tag['name']:'all';
        $order   =  empty($tag['order'])?'sort asc,create_time desc':$tag['order'];
        if(!empty($tag['id'])) { // 获取单个数据
            $parseStr   =  '<data name="'.$name.'" where="id='.$tag['id'].'" field="'.$tag['field'].'" result="'.$result.'" order="'.$order.'" limit="'.$tag['limit'].'">'.$content.'</data>';
        }else{ // 获取数据集
            if(C('PREVIEW_MODE')) {
                $where = '(status=1 OR status=2) ';
            }else{
                $where = 'status=1 ';
            }
            if(strtolower($name) != 'all') {
                $where .= ' AND module=\''.$name.'\' ';
            }
            if(!empty($tag['cate'])) { // 获取某个分类的文章
                if(strpos($tag['cate'],',')) {
                    $where .= ' AND cate_id IN ('.$tag['cate'].')';
                }else{
                    $where .= ' AND cate_id='.$tag['cate'];
                }
            }elseif(!empty($tag['pcate'])){ // 获取频道下面的文章 会自动获取子类的
                $subCateList   =  implode(',',D('Cate')->getSubCateId($tag['pcate'],true,true));
                $where .= ' AND cate_id IN('.$subCateList.')';
            }elseif(!empty($tag['record_id'])){
                $where .= ' AND record_id='.$tag['record_id'];
            }
            if(!empty($tag['pos'])) {
                $where .= ' AND pos!=7 AND pos='.$tag['pos'];
            }
            if(!empty($tag['where'])) {
                $where  .=  ' AND pos!=7 AND '.$tag['where'];
            }
            if(!empty($tag['type'])) {
                switch($tag['type']) {
                    case 'hot':
                        $where  .=  ' AND  map_id=0 AND pos!=7';
                        $order   =  'read_week desc';
                        break;
                    case 'new':
                        $where  .=  ' AND  map_id=0 AND pos!=7';
                        $order   =  'create_time desc';
                        break;
                }
            }
            $parseStr = '<datalist name="'.$name.'" where="'.$where.'" field="'.$tag['field'].'" result="'.$result.'" order="'.$order.'" limit="'.$tag['limit'].'" >'.$content.'</datalist>';
        }
        return $parseStr;
    }

}
?>