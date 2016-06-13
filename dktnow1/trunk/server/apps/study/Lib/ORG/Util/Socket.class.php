<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class Socket extends Think {
    protected $_config = array(
        'persistent'    => false,
        'host'          => 'localhost',
        'protocol'      => 'tcp',
        'port'          => 80,
        'timeout'       => 30
    );

    public $config = array();
    public $connection = null;
    public $connected = false;
    public $error = array();

    public function __construct($config = array()) {
        $this->config   =   array_merge($this->_config,$config);
        if (!is_numeric($this->config['protocol'])) {
            $this->config['protocol'] = getprotobyname($this->config['protocol']);
        }
    }

    function b_fsockopen($host, $port, &$errno, &$errstr, $timeout) {

       $s = socket_create(AF_INET, SOCK_STREAM, 0);
       $r = socket_connect($s, $host, $port);

       if ($r || socket_last_error() == EINPROGRESS) {
           $errno = EINPROGRESS;
           return $s;
       }

       $errno = socket_last_error($s);
       $errstr = socket_strerror($errno);
       socket_close($s);
       return false;
   }

    public function connect() {
        if ($this->connection != null) {
            $this->disconnect();
        }

        if ($this->config['persistent'] == true) {
            $tmp = null;
            $this->connection = @$this->b_fsockopen($this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
        } else {
            $this->connection = $this->b_fsockopen($this->config['host'], $this->config['port'], $errNum, $errStr, $this->config['timeout']);
        }

        if (!empty($errNum) || !empty($errStr)) {
            $this->error($errStr, $errNum);
        }

        $this->connected = is_resource($this->connection);
        return $this->connected;
    }

    public function error() {
    }

    public function write($data) {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }
        return socket_write($this->connection, $data, strlen($data));
    }

    public function read($length=1024) {
        if (!$this->connected) {
            if (!$this->connect()) {
                return false;
            }
        }

        if (!feof($this->connection)) {
            return socket_read($this->connection, $length);
        } else {
            return false;
        }
    }

    public function disconnect() {
        if (!is_resource($this->connection)) {
            $this->connected = false;
            return true;
        }
        $this->connected = !socket_close($this->connection);

        if (!$this->connected) {
            $this->connection = null;
        }
        return !$this->connected;
    }

    public function __destruct() {
        $this->disconnect();
    }

}
?>