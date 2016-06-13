<?php
/**
 * TeacherImportAction
 * 教师导入模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-14
 *
 */
class TeacherImportAction extends CommonAction{

    public function index(){

        // 用户配置
        $authType = C('AUTH_TYPE');

        $template['template'] = 'TeacherDataImport.xls';
        $template['example'] = 'TeacherDataExample.xls';
        $template['authType'] = $authType[2];

        $this->assign('template', $template);
        $this->display();
    }

    public function upload(){

        header("Content-Type:text/html; charset=utf-8");

        // 加载reader类
        import('@.ORG.Util.ExcelReader');

        // 是否有提交
        if (!$this->isPost()) {

            $this->error('请选择导入文件提交');

        }

        $fileName = parent::upload(explode ( ',', 'xls'), C('AUTH_IMPORT_PATH'));
        $fileName = C('AUTH_IMPORT_PATH') . $fileName;

        $data = new ExcelReader();
        $data->setOutputEncoding('utf-8');

        // 读取文件
        $data->read($fileName);

        // 获取行数
        $numRows = $data->sheets[0]['numRows'];

        if ($numRows > 100) {
            $this->error('每次导入教师人数不能超过100人');
        }

        // 性别配置
        $authSex = array_flip(C('AUTH_SEX'));

        // 获取当前学校
        $s_id = $this->authInfo['s_id'];

        $authList = array();

        for ($i = 3; $i <= $numRows; $i++) {

            if(!$data->sheets[0]['cells'][$i]) {
                continue;
            }

            $tmpArray = array();
            // 姓名
            $tmpArray['a_nickname'] = $data->sheets[0]['cells'][$i][2];

            if (empty($tmpArray['a_nickname'])) {
                $this->error('请检查第' . $i . '行姓名列，姓名不可为空');
            }

            // 账号
            $tmpArray['a_account'] = $data->sheets[0]['cells'][$i][3];

            if (empty($tmpArray['a_account'])) {
                $this->error('请检查第' . $i . '行账号列，账号不可为空');
            }

            // 密码
            $tmpArray['a_password'] = md5(getStrLast($tmpArray['a_account']));

            $tmpArray['a_type'] = 2;

            $tmpArray['s_id'] = $s_id;

            // 教师信息

            // 入校时间
            $tmpArray['a_year'] = $data->sheets[0]['cells'][$i][4];

            if (empty($tmpArray['a_year'])) {
                $this->error('请检查第' . $i . '行入校时间列，入校时间为四位年份数字');
            }

            // 课程
            $tmpArray['t_subject'] = $data->sheets[0]['cells'][$i][5];

            if (empty($tmpArray['t_subject'])) {
                $this->error('请检查第' . $i . '行教授课程列，多门课程请以|隔开');
            }


            // 性别
            $tmpArray['a_sex'] = $data->sheets[0]['cells'][$i][6];
            $tmpArray['a_sex'] = $authSex[$tmpArray['a_sex']];
            if (empty($tmpArray['a_sex'])) {
                $this->error('请检查第' . $i . '行性别列，性别列请填写：男或女');
            }

            // 生日
            $tmpArray['a_birthday'] = $data->sheets[0]['cells'][$i][7];
            $tmpArray['a_birthday'] = strtotime($tmpArray['a_birthday']);

            // 地址
            $tmpArray['a_region'] =$data->sheets[0]['cells'][$i][8] .'###'. $data->sheets[0]['cells'][$i][9] .'###'. $data->sheets[0]['cells'][$i][10];

            // 手机号
            $tmpArray['a_tel'] = $data->sheets[0]['cells'][$i][11];
            $tmpArray['a_applications'] = C('DEFAULT_APP');
            if ($tmpArray['a_tel']) {

                if(!preg_match("/(^(13|15|18)[0-9]{9}$)|(^18[5-9]{1}[0-9]{8}$)|(^0{0,1}13[0-9]{9}$)/", $tmpArray['a_tel'])) {
                    $this->error('请检查第' . $i . '行手机列，手机号码为11位有效数字');
                }
            }

            $authList[$i] = $tmpArray;
        }


        // 数据导入
        $error = $this->import($authList);

        if ($error) {
            $errorStr = '';
            foreach ($error as $eKey => $eValue) {
                $errorStr .= $eValue['a_account'] . $eValue['message'] . '<br>';
            }

            echo $errorStr;
        } else {
            $this->success('导入成功');
        }
    }

    // 导入教师数据
    public function import($authList){

        $Auth = M('Auth');
        $error = array();

        foreach($authList as $key => $value){

            $where['a_account'] = $value['a_account'];

            // 判断是否已存在
            if ($Auth->where($where)->getField('a_id')) {
                $error[$key] = $value;
                $error[$key]['message'] = '账号已存在';
            } else {

                // 写入
                $value['a_created'] = time();
                $a_id = $Auth->add($value);

                // 写入成功，后续操作
                if ($a_id) {

                    $this->navigation($a_id);

                    $this->teacher($a_id, $value['t_subject'], $value['s_id']);

                } else {
                    $error[$key] = $value;
                    $error[$key]['message'] = '写入用户失败';
                }
            }
        }

        return $error;
    }

    // 添加教师导航
    public function navigation($a_id) {

        // 添加默认导航
        $navigations = C('NAVIGATION');

        foreach ($navigations as $key => $value) {

            $nav['na_title'] = $value['title'];
            $nav['na_url'] = $value['url'];
            $nav['na_sort'] = $key;
            $nav['a_id'] = $a_id;
            $nav['na_created'] = time();

            M('Navigation')->add($nav);
        }
    }

    // 教师教授课程
    public function teacher($a_id, $t_subject, $s_id){

        $Teacher = M('Teacher');

        // 组织数据
        $courseList = explode('|', $t_subject);
        $courseType = array_flip(C('COURSE_TYPE'));

        // 循环写入教授的课程
        foreach ($courseList as $key => $value) {

            $course['t_subject'] = $courseType[$value];
            $course['a_id'] = $a_id;
            $course['s_id'] =  $s_id;

            $Teacher->add($course);
        }
    }
}
?>