<?php

class AjaxPage {
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter;
    // 默认列表每页显示行数
    public $listRows = 10;
    // 起始行数
    public $firstRow;
    // 分页总页面数
    protected $totalPages;
    // 总行数
    protected $totalRows;
    // 当前页数
    protected $nowPage;
    // 分页的栏的总页数
    protected $coolPages;
    // 分页显示定制
    protected $config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>'%first% %upPage% %prePage% %linkPage% %nextPage% %downPage% %end% %header% %totalPage% %totalRow%');
    // 默认分页变量名
    protected $varPage;

    /**
     *
     * 架构函数
     *
     * @access public
     *
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     *
     */
    public function __construct($totalRows, $listRows='', $parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if (!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages = ceil($this->totalPages/$this->rollPage);
        $this->nowPage = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if (!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name, $value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     *
     * 分页显示输出
     *
     * @access public
     *
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").'&'.$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='javascript:getList(".$upRow.");'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "";
            $theFirst = "<a href='javascript:getList(1);'>".$this->config['first']."</a>";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='javascript:getList(".$downRow.");'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = '';
            $theEnd = "<a href='javascript:getList(".$theEndRow.");'>".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<a href='javascript:getList(".$page.");'>".$page."</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<a class='current' href='javascript:void(0);'>".$page."</a>";
                }
            }
        }

        $totalRows = '<a href="javascript:void(0);">' . $this->totalRows . $this->config['header'] . '</a>';
        $totalPages = '<a href="javascript:void(0);">' . $this->nowPage . '/' .$this->totalPages . '页</a>';

        $pageStr = str_replace(
            array('%header%','%nowPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%','%totalRow%','%totalPage%'),
            array('',$this->nowPage,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd,$totalRows,$totalPages),$this->config['theme']);
        return $pageStr;
    }

}
?>