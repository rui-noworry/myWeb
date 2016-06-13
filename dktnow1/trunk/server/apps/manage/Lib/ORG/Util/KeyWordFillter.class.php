<?php
/**
 * 关键字过滤类
 * param keyword_file 关键字文件
 * dict
 * result
 * */
class fillter{

    private $keyword_file;
    private $dict;
    public $result;

    public function __construct($file){

        if(!is_file($file)){
            trigger_error("$file not exists!");
        }

        $this->keyword_file = $file;
    }

   /**
    * param resource 要过滤的字串
    * */
   public function fill($resource) {

        $this->dict = $this->getDict();
        $len = strlen($resource);

        for($i = 0; $i < $len; ++ $i) {

            $key = substr($resource,$i,2);

            if(array_key_exists($key,$this->dict)) {
                $this->deal(substr($resource, $i, $this->dict[$key]['max']), $key, $af);
                $i+=$af;
            }
            else{

                $this->result .=substr($resource,$i,1);
            }
        }
        return $this->result;
    }

    /**
     * param $res  要替换的字串
     * key 关键字
     * af 替换的位置
     * */
    public function deal($res, $key, &$af){

        $af=0;

        foreach($this->dict[$key]['list'] as $keyword){

            if(strpos($res,$keyword) !==false){

                $len = strlen($keyword);
                $af = $len-1;
                $this->result .=str_repeat("*",$len);
                return;
            }
        }
        $this->result .= substr($res,0,1);
    }

    // 获取关键字列表，并拆分成数组
    private function getKeyWordList(){

        $keywords = file_get_contents($this->keyword_file);
        return array_unique(explode("|",$keywords));
    }

    //获取有效的关键字
    public function getDict(){

        $keywords = $this->getKeyWordList();

        $dict = array();

        foreach ($keywords as $keyword) {
            if (empty($keyword)) {
            continue;
        }

            $key = substr($keyword,0,2);
            $dict[$key]['list'][] = $keyword;
            $dict[$key]['max'] = max($dict[$key]['max'], strlen($keyword));
        }
        return $dict;
    }
}