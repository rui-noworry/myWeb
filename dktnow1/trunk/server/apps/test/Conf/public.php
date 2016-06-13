<?php
return array(
    'ERROR_CODE' => array(

        1 => array(
            'errCode' => 1,
            'errMessage' => 'sign 校验出错',
        ),
        2 => array(
            'errCode' => 2,
            'errMessage' => '参数未传递',
        ),
        3 => array(
            'errCode' => 3,
            'errMessage' => '系统繁忙',
        ),
        4 => array(
            'errCode' => 4,
            'errMessage' => '没有权限操作',
        ),
        5 => array(
            'errCode' => 5,
            'errMessage' => '账号密码错误',
        ),
        6 => array(
            'errCode' => 6,
            'errMessage' => '非法数据操作',
        ),
        7 => array(
            'errCode' => 7,
            'errMessage' => '无符合条件的数据',
        ),
    ),

    'IMG_SITE' => 'http://pic.dkt.com/',
    'IMG_SITE2' => 'http://192.168.7.53:81/',
    'API_URL' => 'http://new.dkt.com/apps/manage/Api/',

    // API接口类
    'CONTROLLER' => array(
        'Public'  => array(
            'title' => '公共类',
            'info' => '',
            'function'=> array(
                'clientInit' => array(
                    'title' => '初始化',
                    'param'=> array(
                        'num' => array(
                            'title' => '随机数',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
                'login' => array(
                    'title' => '登录',
                    'param'=> array(
                        'username' => array(
                            'title' => '账号',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'password' => array(
                            'title' => '密码',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'version' => array(
                            'title' => '版本号',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'versionType' => array(
                            'title' => '版本类型（ANDROID OR IOS）',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'clientType' => array(
                            'title' => '客户端版本类型（ANDROID OR IOS）',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'type' => array(
                            'title' => '类型',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Classwork' => array(
            'title' => '练习类',
            'function'=> array(
                'publish' => array(
                    'title' => '教师发布练习',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'complete_time' => array(
                            'title' => '完成时间',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
                'studentClassworkList' => array(
                    'title' => '学生获取课时下练习列表并做作业',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '学生ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page_size' => array(
                            'title' => '每页显示数量',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page' => array(
                            'title' => '页码',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'insert' => array(
                    'title' => '学生提交练习',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cw_id' => array(
                            'title' => '练习ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cd_answer' => array(
                            'title' => '练习答案，JSON格式',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cd_percent' => array(
                            'title' => '百分比',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'cd_use_time' => array(
                            'title' => '练习用时',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'picture_answer' => array(
                            'title' => '简答题图片',
                            'type' => 'array',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'correct' => array(
                    'title' => '教师检查学生练习',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'stu_id' => array(
                            'title' => '学生ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cw_id' => array(
                            'title' => '练习ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'doClasswork' => array(
                    'title' => '学生做练习',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'ap_id' => array(
                            'title' => '发布练习ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'deletePictureAnswer' => array(
                    'title' => '学生删除简答题图片',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'filename' => array(
                            'title' => '文件名',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'stats' => array(
                    'title' => '练习统计',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'lists' => array(
                    'title' => '获取练习列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page' => array(
                            'title' => '页码',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page_size' => array(
                            'title' => '每页显示条数',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'h_course' => array(
                            'title' => '学科',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'listsAuth' => array(
                    'title' => '教师查看练习的学生列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'ap_id' => array(
                            'title' => '练习ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),

            ),
        ),
        'Homework' => array(
            'title' => '作业类',
            'function'=> array(
                'publish' => array(
                    'title' => '教师发布作业',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'complete_time' => array(
                            'title' => '完成时间',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
                'stats' => array(
                    'title' => '作业统计',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'todayWork' => array(
                    'title' => '获取今日作业列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'lists' => array(
                    'title' => '获取作业列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page' => array(
                            'title' => '页码',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page_size' => array(
                            'title' => '每页显示条数',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'h_course' => array(
                            'title' => '学科',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'listsAuth' => array(
                    'title' => '教师查看作业的学生列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'ap_id' => array(
                            'title' => '作业ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'studentHomeworkList' => array(
                    'title' => '学生获取课时下作业列表并做作业',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page_size' => array(
                            'title' => '每页显示条数',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page' => array(
                            'title' => '页码',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'insert' => array(
                    'title' => '学生提交作业',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '学生ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'h_id' => array(
                            'title' => '作业ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'hd_answer' => array(
                            'title' => '作业答案，JSON格式',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'hd_percent' => array(
                            'title' => '百分比',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'hd_use_time' => array(
                            'title' => '作业用时',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'picture_answer' => array(
                            'title' => '简答题图片',
                            'type' => 'array',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'correct' => array(
                    'title' => '教师检查学生作业',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '教师ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'stu_id' => array(
                            'title' => '学生ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'h_id' => array(
                            'title' => '作业ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'setStatus' => array(
                    'title' => '教师设置学生重做或通过',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '教师ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'stu_id' => array(
                            'title' => '学生ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'hd_id' => array(
                            'title' => '作业答案ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'hd_status' => array(
                            'title' => '作业答案状态1：重做4：完成',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'hd_remark' => array(
                            'title' => '教师批语',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'hd_persent' => array(
                            'title' => '百分比',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'hd_score' => array(
                            'title' => '分数',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'hd_stat' => array(
                            'title' => '作业统计',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'hd_shortanswer' => array(
                            'title' => '简答题正误',
                            'type' => 'string',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'doHomework' => array(
                    'title' => '学生做作业',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'ap_id' => array(
                            'title' => '发布作业ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'deletePictureAnswer' => array(
                    'title' => '学生删除简答题图片',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'filename' => array(
                            'title' => '文件名',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Class' => array(
            'title' => '班级类',
            'function'=> array(
                'students' => array(
                    'title' => '学生列表',
                    'info' => '',
                    'param'=> array(
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'lists' => array(
                    'title' => '获取班级列表',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Trend' => array(
            'title' => '动态类',
            'function'=> array(
                'lists' => array(
                    'title' => '获取动态列表',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page_size' => array(
                            'title' => '每页显示条数',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page' => array(
                            'title' => '页码',
                            'type' => 'int',
                            'required' => '0',
                        ),
                    ),
                ),
            ),
        ),
        'Course' => array(
            'title' => '课程类',
            'function'=> array(
                'lists' => array(
                    'title' => '课程列表',
                    'info' => '获取用户当前的班级下的课程列表（教师，学生身份）',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        's_id' => array(
                            'title' => '学校ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Lesson' => array(
            'title' => '课文类',
            'function'=> array(
                'lists' => array(
                    'title' => '获取课文列表(注：略过单元)',
                    'info' => '',
                    'param'=> array(
                        'co_id' => array(
                            'title' => '课程ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Classhour' => array(
            'title' => '课时类',
            'function'=> array(
                'lists' => array(
                    'title' => '获取课时列表',
                    'info' => '',
                    'param'=> array(
                        'l_id' => array(
                            'title' => '课文ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'co_id' => array(
                            'title' => '课程ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'publish' => array(
                    'title' => '课时发布',
                    'info' => '',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Tache' => array(
            'title' => '环节类',
            'function'=> array(
                'lists' => array(
                    'title' => '获取环节列表',
                    'info' => '',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Activity' => array(
            'title' => '活动类',
            'function'=> array(
                'lists' => array(
                    'title' => '获取活动列表',
                    'info' => '',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'ta_id' => array(
                            'title' => '环节ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'act_type' => array(
                            'title' => '活动类型：0：所有，其他：对应类型',
                            'type' => 'int',
                            'required' => '0',
                        ),
                    ),
                ),
                'detail' => array(
                    'title' => '获取活动详情',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'publish' => array(
                    'title' => '教师发布活动(不包括练习和作业)',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'act_type' => array(
                            'title' => '活动类型',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
                'activityTalk' => array(
                    'title' => '讨论接口',
                    'info' => '',
                    'param'=> array(
                        'ap_id' => array(
                            'title' => '已发布的活动ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'at_content' => array(
                            'title' => '讨论内容',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
                'talks' => array(
                    'title' => '讨论列表',
                    'info' => '',
                    'param'=> array(
                        'act_id' => array(
                            'title' => '活动ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'ap_id' => array(
                            'title' => '已发布的活动ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Crowd' => array(
            'title' => '群组类',
            'function'=> array(
                'authList' => array(
                    'title' => '群组成员',
                    'info' => '',
                    'param'=> array(
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'lists' => array(
                    'title' => '获取群组列表',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户id',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'type' => array(
                            'title' => '类型',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'ResourceNote' => array(
            'title' => '视频时间点笔记',
            'function'=> array(
                'saveNote' => array(
                    'title' => '添加时间点笔记(学生)',
                    'info' => '',
                    'param'=> array(
                        're_id' => array(
                            'title' => '资源ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'rn_time_point' => array(
                            'title' => '时间点，格式：01:35:13',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'rn_content' => array(
                            'title' => '内容',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户编号',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'lists' => array(
                    'title' => '获取时间点笔记列表(学生)',
                    'info' => '',
                    'param'=> array(
                        're_id' => array(
                            'title' => '资源ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'page' => array(
                            'title' => '页面ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'page_size' => array(
                            'title' => '页面ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'update' => array(
                    'title' => '更新时间点笔记(学生)',
                    'info' => '',
                    'param'=> array(
                        'rn_id' => array(
                            'title' => '时间点笔记ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'rn_content' => array(
                            'title' => '内容',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
                'delete' => array(
                    'title' => '删除时间点笔记(学生)',
                    'info' => '',
                    'param'=> array(
                        'rn_id' => array(
                            'title' => '时间点笔记ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'int',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'ClickResource' => array(
            'title' => '资源点击量',
            'function'=> array(
                'insertNum' => array(
                    'title' => '记录点击量',
                    'info' => '',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '用户编号',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        're_id' => array(
                            'title' => '资源ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        're_type' => array(
                            'title' => '资源类型，默认为 1， 大课堂：1，数字学校：2',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'Resource'  => array(
            'title' => '资源类',
            'function'=> array(
                'listByClasshour' => array(
                    'title' => '获取课时下的资源',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cro_id' => array(
                            'title' => '群组ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'c_id' => array(
                            'title' => '班级ID',
                            'type' => 'int',
                            'required' => '0',
                        ),
                    ),
                ),
            ),
        ),
        'StudentNotes'  => array(
            'title' => '学生笔记类',
            'function'=> array(
                'upload' => array(
                    'title' => '上传笔记',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        /*'stu_notes' => array(
                            'title' => '笔记文件',
                            'type' => 'file',
                            'required' => '1',
                        )*/
                    ),
                ),
                'get' => array(
                    'title' => '获取笔记',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'stu_notes' => array(
                            'title' => '笔记文件',
                            'type' => 'file',
                            'required' => '1',
                        )
                    ),
                ),
            ),
        ),
        'ClasshourPackage'  => array(
            'title' => '课程包类',
            'function'=> array(
                'insert' => array(
                    'title' => '教师添加课时包',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'co_id' => array(
                            'title' => '课程ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cpa_title' => array(
                            'title' => '课时包名称',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cpa_status' => array(
                            'title' => '是否为模板',
                            'type' => 'int',
                            'required' => '0',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cpa_file' => array(
                            'title' => '课时包文件',
                            'type' => 'file',
                            'required' => '1',
                        )
                    ),
                ),
                'lists' => array(
                    'title' => '课时包列表',
                    'param'=> array(
                        'cl_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        )
                    ),
                ),
                'remove' => array(
                    'title' => '删除课时包',
                    'param'=> array(
                        'cpa_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        )
                    ),
                ),
                'update' => array(
                    'title' => '更新课时包',
                    'param'=> array(
                        'cpa_id' => array(
                            'title' => '课时ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'a_id' => array(
                            'title' => '用户ID',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'cpa_title' => array(
                            'title' => '课时包名称',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cpa_status' => array(
                            'title' => '是否为模板',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                        'cpa_file' => array(
                            'title' => '课时包文件',
                            'type' => 'file',
                            'required' => '1',
                        )
                    ),
                ),
            ),
        ),
        'Auth'  => array(
            'title' => '用户上传图片',
            'info' => '',
            'function'=> array(
                'picture' => array(
                    'title' => '用户上传图片',
                    'param'=> array(
                        'a_id' => array(
                            'title' => '账号',
                            'type' => 'int',
                            'required' => '1',
                        ),
                        'picture' => array(
                            'title' => '图片',
                            'type' => 'file',
                            'required' => '1',
                        ),
                        'skey' => array(
                            'title' => 'SKEY',
                            'type' => 'string',
                            'required' => '1',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
?>