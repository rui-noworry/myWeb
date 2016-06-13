<?php

class IndexAction extends Action {

    private $format = 'JSON';

    public function index(){

        $this->controller = C('CONTROLLER');
        $this->display();
    }

    public function getFunction() {

        $controller = C('CONTROLLER');
        echo json_encode($controller[$_POST['con']]['function']);
    }

    public function getParam() {

        $controller = C('CONTROLLER');
        echo json_encode($controller[$_POST['con']]['function'][$_POST['fun']]['param']);
    }

    public function api() {

        $params['method'] = $_POST['controller'] . '.' . $_POST['function'];
        $params['args'] = $_POST['require'];
        $params['ts'] = time();
        $params['format'] = 'JSON';

        if ($_POST['param']) {
            $_POST['param'] = explode('&', preg_replace('/\n|\r\n/', '&', $_POST['param']));

            foreach ($_POST['param'] as $key => $value) {
                $tmp = explode('=', $value);
                $params['args'][$tmp[0]] = $tmp[1];
            }
        }

        unset($params['args']['skey']);
        $params['skey'] = $_POST['require']['skey'];
        $skey = urldecode($params['skey']);

        $params = $this->initParams($params['method'], $params['args'], $params['ts'], $skey);

        $res['param1'] = $this->generateSign($params);
        $params['sign'] = md5($res['param1']);

        $res['param2'] = $this->buildUrl($params);

        $data = $this->doRequest(C('API_URL'), $res['param2']);

        if (strpos($data[1], 'errCode') !== FALSE) {
            $err = get_object_vars(json_decode($data[1]));
            $errCode = C('ERROR_CODE');
            $res['obj'] = $errCode[$err['errCode']]['errMessage'];
        } else {
            $res['obj'] = $this->turnObj(json_decode($data[1]));
        }

        $res['str'] = strval($data[1]);
        echo json_encode($res);
    }

    public function turnObj($arr, $str = '') {

        $str .= '<table cellpadding="0" cellspacing="0" width="100%">';

        if (is_object($arr)) {
            $arr = get_object_vars($arr);
        }

        $str .= '<tr><td>总数</td><td>' . count($arr) . '</td></tr>';

        foreach ($arr as $key => $value) {
            $str .= '<tr><td>' .$key . '</td><td>';

            if (is_array($value) || is_object($value)) {
                $str .= $this->turnObj($value);
            } else {

                if (strpos($value, '/apps/Uploads') !== FALSE && !in_array($key, array('ar_savename', 'stu_notes', 'version'))) {
                    $str .= '<img src="' . $value . '" />';
                } else {
                    $str .= $value;
                }
            }
            $str .= '</td></tr>';
        }
        $str .= '</table>';

        return $str;

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

        return $this->buildUrl($request);
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
    public function doRequest($url, $data) {

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
}