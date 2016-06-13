<?php
/**
 * OpenAction
 * Api基类
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-10
 *
 */
class OpenAction extends Action {

    protected $format = 'JSON';
    protected $errCode = array();
    protected $auth = array();

    // 初始化
    public function _initialize() {

        $this->errCode = C('ERROR_CODE');

        // 需要对汉字做URL_DECODE处理的参数名
        $haveUrlDecode = C('HAVE_URL_DECODE');
        $haveMagicQuotes = C('HAVE_MAGIC_QUOTES');

        foreach ($_POST['args'] as $key => $value) {
            if (in_array($key, $haveUrlDecode)) {
                $_POST['args'][$key] = urldecode($value);
            }
        }
        // 需要验证SESSION的方法;
        if (!in_array(trim($_POST['method']), C('NOT_VERIFY_METHOD')) || in_array(strtolower(ACTION_NAME), C('VERIFY_ACTION'))) {

            // 若未传递skey
            if (!$_POST['skey'] || !$_POST['args']['a_id']) {
                $this->ajaxReturn($this->errCode[2]);
            }

            $skey = urldecode($_POST['skey']);
            if ($this->apiLogin($skey) != intval($_POST['args']['a_id'])) {
                $this->ajaxReturn($this->errCode[4]);
            }
        }

        $params = $this->initParams($_POST['method'], $_POST['args'], $_POST['ts'], $skey);

        if ($_POST['sign'] != $this->generateSign($params)) {

            $this->ajaxReturn($this->errCode[1]);
        }

        foreach ($_POST['args'] as $key => $value) {
            if (in_array($key, $haveMagicQuotes)) {
                $_POST['args'][$key] = addslashes($_POST['args'][$key]);
            }
        }
        reloadCache();
        cacheData();
        /*
        $_POST['method'] = 'Auth.login';
        $_POST['args']['username'] = '654321';
        $_POST['args']['password'] = '654321';
        $_POST['ts'] = time();
        $_POST['format'] = 'JSON';

        $params = $this->initParams($_POST['method'], $_POST['args'], $_POST['ts'], $skey);
        $params['sign'] = $this->generateSign($params);
        echo $this->buildUrl($params);
        */
    }

    // 登录
    protected function apiLogin($skey) {

        $a_id = intval(passport_decrypt(urldecode($skey), ILC_ENCRYPT_KEY));

        $this->auth = M('Auth')->where(array("a_id" => $a_id))->find();

        $id = $this->auth['a_id'];

        return $id? $id: FALSE;
    }

    /**
     * initParams
     * 必要参数初始化
     *
     * @param  mixed $method
     * @return void
     */
    protected function initParams($method, $args, $ts, $skey = '') {

        $params = array();
        $params['format'] = strtoupper($this->format);
        $params['ts'] = $ts;
        $params['method'] = $method;
        ksort($args);
        $params['args'] = $args;

        if ($skey) {
            $params['skey'] = $skey;
        }
        return $params;
    }

    /**
     * generateSign
     * sig生成方法
     *
     * @param  mixed $params
     * @param  mixed $method
     * @param  mixed $args
     * @return void
     */
    protected function generateSign($params) {

        $fields = array('method', 'ts', 'args', 'format', 'skey');
        sort($fields);

        $request = array();
        foreach ($fields as $k) {

            if ($k != 'skey' || $params['skey']){
                $request[$k] = $params[$k];
            }
        }

        return md5($this->buildUrl($request));
    }

    /**
     * buildUrl
     * 组装 url
     *
     * @param  array $request
     * @return string
     **/
    protected function buildUrl($request) {

        return http_build_query($request, '', '&');
    }

    /**
     * postRequest
     *
     * @param  mixed $url
     * @param  mixed $data
     * @return void
     */
    public function postRequest($url, $data) {

        if (function_exists('curl_init')) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Meishi API PHP Client 0.1 (curl) ' . phpversion());
            $result = curl_exec($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            return array($errno, $result);

        } else {

            $context =
            array('http' =>
                    array('method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded'."\r\n".
                                    'User-Agent: Meishi API PHP Client 0.1 (non-curl) '.phpversion()."\r\n".
                                    'Content-length: ' . strlen($data),
                        'content' => $data));
            $contextid = stream_context_create($context);
            $sock = fopen($url, 'r', false, $contextid);
            if ($sock) {
                $result = '';
                while (!feof($sock)) {
                    $result .= fgets($sock, 4096);
                }
                fclose($sock);
            }

            return array(0, $result);
        }
    }

    // 生成令牌
    protected function saveToken(){
        $_SESSION['think_token']  =  md5(microtime(TRUE));
    }

    // 验证令牌
    protected function isValidToken($reset=false){
        if($_REQUEST['think_token']==$_SESSION['think_token']){
            $valid=true;
            $this->saveToken();
        }else {
            $valid=false;
            if($reset)    $this->saveToken();
        }
        return $valid;
    }

    // 默认写入数据
    public function insertData() {

        $model = D($this->getActionName());

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        return $model->add();
    }

    // 默认写入操作
    public function insert() {

        //保存当前数据对象
        $result = $this->insertData();

        $this->show($result);
    }

    // 默认显示操作
    public function show($result) {

        //保存当前数据对象
        if (false !== $result) {

            $jumpUrl = '/' . GROUP_NAME . '/' . $this->getActionName() . '/index';
            //成功提示
            $this->assign('jumpUrl', $jumpUrl);
            $this->success('成功');
        } else {

            //失败提示
            $this->error('失败');
        }
    }

    // 默认更新数据
    public function updateData() {

        $model = D($this->getActionName());

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        // 更新数据
        return $model->save();
    }

    // 默认更新操作
    public function update() {

        // 更新数据
        $result == $this->updateData();

        $this->show($result);
    }

    // 上传
    public function upload($allowType, $savePath, $thumb = FALSE, $width = '', $height = '', $prefix = '', $maxSize='', $remove = FALSE, $rule = 'uniqid') {

        import("@.ORG.Net.UploadFile");

        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = empty($maxSize)?C('MAX_UPLOAD_FILE_SIZE'):$maxSize;

        if ($thumb) {
            $upload->thumb = $thumb;
            $upload->thumbPrefix = $prefix;
            $upload->thumbMaxWidth = $width;
            $upload->thumbMaxHeight = $height;
            $upload->thumbRemoveOrigin = $remove;
        }

        //设置上传文件类型
        $upload->allowExts = $allowType;
        //设置附件上传目录
        $upload->savePath = $savePath;
        //设置上传文件规则
        $upload->saveRule = $rule;
        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $info = $upload->getUploadFileInfo();
            return $info[0]['savename'];
        }
    }
}
?>