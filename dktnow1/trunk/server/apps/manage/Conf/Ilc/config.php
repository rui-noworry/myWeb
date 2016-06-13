<?php
return array(
    'URL_DISPATCH_ON'       => 1,
    'TOKEN_ON'              => FALSE,
    'USER_AUTH_ON'          => true,
    'SESSION_EXPIRE'        => 3*60*60,
    'URL_CASE_INSENSITIVE'  => FALSE,
    'USER_AUTH_TYPE'        => 1,
    'RBAC_ROLE_TABLE'       => 'ilc_role',
    'RBAC_USER_TABLE'       => 'ilc_role_user',
    'RBAC_ACCESS_TABLE'     => 'ilc_access',
    'RBAC_NODE_TABLE'       => 'ilc_node',
    'USER_AUTH_KEY'         => 'authId',
    'ADMIN_AUTH_KEY'        => 'administrator',
    'USER_AUTH_MODEL'       => 'User',
    'AUTH_PWD_ENCODER'      => 'md5',
    'USER_AUTH_GATEWAY'     => __GROUP__ . '/Public/login',
    'NOT_AUTH_MODULE'       => 'Public,Index',
    'REQUIRE_AUTH_MODULE'   => '',
    'NOT_AUTH_ACTION'       => '',
    'REQUIRE_AUTH_ACTION'   => '',
    'GUEST_AUTH_ON'         => FALSE,
    'GUEST_AUTH_ID'         => 0,
    'SHOW_RUN_TIME'         => FALSE,
    'SHOW_PAGE_TRACE'       => FALSE,
    'SHOW_ADV_TIME'         => TRUE,
    'SHOW_DB_TIMES'         => TRUE,
    'SHOW_CACHE_TIMES'      => TRUE,
    'SHOW_USE_MEM'          => TRUE,
    'LIKE_MATCH_FIELDS'     => 'title',
    'TAG_NESTED_LEVEL'      => 3,
    'UPLOAD_FILE_RULE'      => 'uniqid',
);
?>