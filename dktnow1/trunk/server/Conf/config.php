<?php
return array(
    'URL_MODEL'         => 2,
    'TOKEN_ON'          => FALSE,
    'DB_TYPE'           => 'mysql',
    'DB_HOST'           => 'localhost',
    'DB_NAME'           => 'dkt',
    'DB_USER'           => 'root',
    'DB_PWD'            => '',
    'DB_PORT'           => '3306',
    'DB_PREFIX'         => 'dkt_',
    'CACHE_TYPE'        => 'file',
    'PAGE_LISTROWS'     => 20,
    'DB_FIELD_CACHE'    => true,
    'HTML_CACHE_ON'     => true,
    'LOAD_EXT_CONFIG'   => 'public',
    'URL_404_REDIRECT'  => '/Public/404',
    'HTML_PATH'         => './html/',
    'HTML_FILE_SUFFIX'  => '.shtml',
    'URL_HTML_SUFFIX'   => 'shtml',
    'TMPL_ACTION_ERROR'     => 'Public:error',
    'TMPL_ACTION_SUCCESS'   => 'Public:success',
    'OS_CONFIG'         => true,
    'APPS_LIST'         => array(
        '', 'study', 'resource', 'manage'
    ),
    // 配置缓存路径
    'CONFIG_TMP_PATH' => './apps/Uploads/Config/',

);
?>