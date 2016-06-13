<?php
return array(
    // 登录 Cookie保存时间: 例如 1天
    'LOGIN_COOKIE_SAVE_TIME' => 1,

    // 获取7天内的动态 例如：7天之内
    'TREND_WITH_IN_TIME' => 7,

    // 学校申请时间限制 例如：1天之内不得重复申请
    'SCHOOL_APPLY_TIME_LIMIT' => 1,

    // 获取时间段内的作业列表 例如：1周之内
    'HOMEWORKLIST_WITH_IN' => array(

        1 => array(
            'title' => '最近一周',
            'sql' => '3600 * 24 * 7',
        ),
        2 => array(
            'title' => '最近一个月',
            'sql' => '3600 * 24 * 30',
        ),
        3 => array(
            'title' => '最近三个月',
            'sql' => '3600 * 24 * 90',
        ),
    ),

    // 动态类型
    'TREND_TYPE' => array(
        1 => array(
            'name' => '作业',
            'action' => array(
                1 => '提交',
                2 => '布置',
                3 => '批改',
                4 => '修改',
            ),
        ),
        2 => array(
            'name' => '练习',
            'action' => array(
                1 => '提交',
                2 => '布置',
                3 => '批改',
                4 => '修改',
            ),
        ),
        3 => array(
            'name' => '投票',
            'action' => array(
                1 => '参与',
                2 => '发起',
            ),
        ),
        4 => array(
            'name' => '群组',
            'action' => array(
                1 => '加入',
                2 => '申请加入',
            ),
        ),
        5 => array(
            'name' => '课程',
            'action' => array(
                1 => '添加',
                2 => '修改',
                3 => '发布',
            ),
        ),
        6 => array(
            'name' => '课文',
            'action' => array(
                1 => '添加',
                2 => '修改',
            ),
        ),
        7 => array(
            'name' => '课时',
            'action' => array(
                1 => '添加',
                2 => '修改',
                3 => '发布',
            ),
        ),
    ),
    'ALLOW_ACTION' => array(
        'public',
        'index',
        'crontab',
        'resource',
        'search'
    ),
);
?>