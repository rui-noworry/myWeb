<?php
/**
 * CacheAction
 * 缓存管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-29
 *
 */
class CacheAction extends CommonAction{

    // 刷新缓存
    public function index(){
        $this->display();
    }

    public function buildCache(){
        $type = $_POST['type'];
        foreach ($type as $val){
            $this->$val();
        }
        $this->success('缓存生成成功！');
    }

    // 配置缓存
    public function config(){
        import('@.ORG.Io.Dir');
        if(is_dir('./Runtime/Data/')) {
            Dir::del('./Runtime/Data/');
        }
        // 生成配置缓存
        $list = M("Config")->getField('con_name,con_value');
        saveCache('config', array_change_key_case($list,CASE_UPPER));
    }

    // 字段缓存
    protected function field() {
        import('@.ORG.Io.Dir');
        if(is_dir('./Runtime/Data/_fields/')) {
            Dir::del('./Runtime/Data/_fields/');
        }
    }

    // 模板缓存
    protected function template() {
        import('@.ORG.Io.Dir');

        if(is_dir('./Runtime/Cache/')) {
            Dir::del('./Runtime/Cache/');
        }
    }

    // 静态文件
    protected function html() {

        return;
        import('@.ORG.Io.Dir');

        $path = C('HTML_PATH');
        if(is_dir($path)) {
            $dir = scandir($path);
            foreach ($dir as $file){
                if(is_file($path.$file)) {
                    unlink($path.$file);
                }elseif('.' != $file && '..' !=$file){
                    Dir::delDir($path.$file);
                }
            }
        }
    }

    // 分组缓存
    protected function group() {
        $list = M("Group")->where(array('g_status' => 1))->order('g_sort')->getField('g_id,g_title');
        saveCache('group', $list);
    }

    // 模型缓存
    protected function model(){
        // 生成文档模型缓存
        $list  = M("Model")->where(array('m_status' => 1))->select();
        $array = array();
        foreach ($list as $key=>$val){
            $map['at_id'] = array('IN', $val['m_list']);
            $val['attrs'] = M('Attribute')->where($map)->getField('at_id,at_name');
            $array[strtolower($val['m_name'])] = $val;
        }
        saveCache('model',$array);
    }

    // 平台
    public function apps() {

        import('@.ORG.Io.Dir');

        if(is_dir('../resource/Runtime/Cache/')) {
            Dir::delDir('../resource/Runtime/Cache/');
        }

        if(is_dir('../resource/Runtime/Data/')) {
            Dir::del('../resource/Runtime/Data/');
        }

        if(is_dir('../study/Runtime/Cache/')) {
            Dir::delDir('../study/Runtime/Cache/');
        }

        if(is_dir('../study/Runtime/Data/')) {
            Dir::del('../study/Runtime/Data/');
        }

        if(is_dir('../study/Runtime/Data/')) {
            Dir::del('../study/Runtime/Data/');
        }

        if(is_dir('../resource/html/')) {
            Dir::del('../resource/html/');
        }
    }
}
?>