<?php
/**
 * StudentImportAction
 * 学生导入模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-14
 *
 */
class StudentImportAction extends CommonAction{

    public function index(){

        // 用户配置
        $authType = C('AUTH_TYPE');

        $template['template'] = 'StudentDataImport.xls';
        $template['example'] = 'StudentDataExample.xls';
        $template['authType'] = $authType[1];

        $this->assign('template', $template);
        $this->leftOn = 4;
        $this->display();
    }

    public function upload(){

        header("Content-Type:text/html; charset=utf-8");

        // 加载reader类
        import('@.ORG.Util.ExcelReader');

        // 是否有提交
        if (!$this->isPost()) {

            $this->error('请选择导入文件提交');

        } else {

            $fileName = parent::upload(explode ( ',', 'xls'), C('AUTH_IMPORT_PATH'));

            $fileName = C('AUTH_IMPORT_PATH') . $fileName;

            $data = new ExcelReader();
            $data->setOutputEncoding('utf-8');

            // 读取文件
            $data->read($fileName);

            // 获取行数
            $numRows = $data->sheets[0]['numRows'];

            if ($numRows > 100) {
                $this->error('请您按班级分批次导入，每班学生人数最多为100人');
            }

            // 用户公共配置

            // 性别配置
            $authSex = array_flip(C('AUTH_SEX'));

            // 获取当前学校的学制
            $school = loadCache('school');

            $school_type = $school[$this->authInfo['s_id']]['s_type'];

            foreach (C('SCHOOL_TYPE') as $key => $value) {
                if (in_array($key, explode(',', $school_type))) {
                    $schoolType[$key] = $value;
                }
            }

            $schoolType = array_flip($schoolType);

            // 年级配置
            $gradeType = C('GRADE_TYPE');

            foreach ($gradeType as $key => $value) {
                $gradeType[$key] = array_flip($value);
            }

            // 获取当前学校
            $s_id = $this->authInfo['s_id'];

            // 班级数据
            $class = M('Class')->where(array('s_id' => $s_id, 'c_is_graduation' => 0))->field('c_id, s_id, c_type, c_grade, c_title')->select();

            $classData = array();
            foreach ($class as $cKey => $cValue) {
                $classStr = $cValue['s_id'] . $cValue['c_type'] . $cValue['c_grade'] . $cValue['c_title'];
                $classData[$classStr] = $cValue['c_id'];
            }

            $authList = array();

            for ($i = 3; $i <= $numRows; $i++) {

                if(!$data->sheets[0]['cells'][$i]){
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

                $tmpArray['a_type'] = 1;

                $tmpArray['s_id'] = $s_id;

                // 学生信息验证验证

                // 学制
                $tmpArray['a_school_type'] = $data->sheets[0]['cells'][$i][4];

                if ($schoolType[$tmpArray['a_school_type']]) {
                    $tmpArray['a_school_type'] = $schoolType[$tmpArray['a_school_type']];
                } else {
                    $this->error('请检查第' . $i . '行学制列，是不是与您所在学校的学制一致');
                }

                if (empty($tmpArray['a_school_type'])) {
                    $this->error('请检查第' . $i . '行学制列');
                }

                // 入学年级
                $tmpArray['a_year'] = $data->sheets[0]['cells'][$i][5];
                $tmpArray['a_year'] = $gradeType[$tmpArray['a_school_type']][$tmpArray['a_year']];

                if (empty($tmpArray['a_year'])) {
                    $this->error('请检查第' . $i . '行年级列，年级请与学制对应检查');
                }

                // 当前班级
                $tmpArray['c_id'] = $data->sheets[0]['cells'][$i][6];

                if (empty($tmpArray['c_id'])) {
                    $this->error('请检查第' . $i . '行班级列，班级不可为空');
                }

                // 组织班级字串
                $listClassStr = $s_id . $tmpArray['a_school_type'] . GradeToYear($tmpArray['a_year'], $s_id) . trim($tmpArray['c_id']);

                // 验证班级是否存在
                if ($classData[$listClassStr]) {
                    $tmpArray['c_id'] = $classData[$listClassStr];
                } else {

                    // 添加班级
                    $classTmpdata = array();
                    $classTmpdata['c_graduation'] = intval(count($gradeType[$tmpArray['a_school_type']])) - intval($tmpArray['a_year']) + intval(GradeToYear($tmpArray['a_year'], $s_id)) + 1;
                    $classTmpdata['c_created'] = time();
                    $classTmpdata['s_id'] = $s_id;
                    $classTmpdata['c_type'] = $tmpArray['a_school_type'];
                    $classTmpdata['c_grade'] = GradeToYear($tmpArray['a_year'], $s_id);
                    $classTmpdata['c_title'] = $tmpArray['c_id'];
                    $classTmpdata['c_is_graduation'] = 0;

                    $c_id = M('Class')->add($classTmpdata);

                    $classData[$listClassStr] = $c_id;
                    $tmpArray['c_id'] = $c_id;
                }

                $tmpArray['a_year'] = GradeToYear($tmpArray['a_year'], $s_id);


                // 性别
                $tmpArray['a_sex'] = $data->sheets[0]['cells'][$i][7];
                $tmpArray['a_sex'] = $authSex[$tmpArray['a_sex']];
                if (empty($tmpArray['a_sex'])) {
                    $this->error('请检查第' . $i . '行性别列，性别列请填写：男或女');
                }

                // 生日
                $tmpArray['a_birthday'] = $data->sheets[0]['cells'][$i][8];
                $tmpArray['a_birthday'] = strtotime($tmpArray['a_birthday']);

                // 地址
                $tmpArray['a_region'] =$data->sheets[0]['cells'][$i][9] .'###'. $data->sheets[0]['cells'][$i][10] .'###'. $data->sheets[0]['cells'][$i][11];
                $tmpArray['a_applications'] = C('DEFAULT_APP');

                // 手机号
                $tmpArray['a_tel'] = $data->sheets[0]['cells'][$i][12];

                if ($tmpArray['a_tel']) {

                    if(!preg_match("/(^(13|15|18)[0-9]{9}$)|(^18[5-9]{1}[0-9]{8}$)|(^0{0,1}13[0-9]{9}$)/", $tmpArray['a_tel'])) {
                        $this->error('请检查第' . $i . '行手机列，手机号码为11位有效数字');
                    }
                }

                $authList[$i] = $tmpArray;
            }
        }

        // 数据导入
        $error = $this->import($authList);

        if ($error) {
            $errorStr = '';
            foreach ($error as $eKey => $eValue) {
                $errorStr .= $eKey . '行' . $eValue['message'] . '<br>';
            }

            echo $errorStr;
        } else {
            $this->success('导入成功');
        }

    }

    // 导入数据
    public function import($authList){

        $Auth = M('Auth');
        $error = array();

        foreach($authList as $key => $value){

            $where['a_account'] = $value['a_account'];

            // 判断账号是否已存在
            if ($Auth->where($where)->getField('a_id')) {
                $error[$key] = $value;
                $error[$key]['message'] = '账号已存在';
            } else {

                // 写入
                $value['a_created'] = time();
                $a_id = $Auth->add($value);

                // 写入成功，后续操作
                if ($a_id) {

                    // 写入班级
                    if ($this->classStudent($a_id, $value['c_id'], $value['s_id'])) {

                        $error[$key] = $value;
                        $error[$key]['message'] = '写入学生班级关系失败';
                    }

                    // 写入在校信息
                    if ($this->authSchool($a_id, $value)) {

                        $error[$key] = $value;
                        $error[$key]['message'] = '写入学生在校时间失败';
                    }

                    // 班级人数增加
                    M('Class')->setInc('c_peoples', array('c_id' => $value['c_id']));

                } else {
                    $error[$key] = $value;
                    $error[$key]['message'] = '写入用户失败';
                }
            }
        }

        return $error;
    }

    // 学生班级关系
    public function classStudent($a_id, $c_id, $s_id) {

        // 组织数据
        $data['a_id'] = $a_id;
        $data['c_id'] = $c_id;
        $data['s_id'] = $s_id;

        // 写入
        if (!M('ClassStudent')->add($data)) {
            return 'error';
        }

        // 班级人数增1
        M('class')->where(array('c_id' => $c_id))->setInc('c_peoples');
    }

    // 学生在校时间
    public function authSchool($a_id, $data) {

        $result['a_id'] = $a_id;
        $result['s_id'] = $data['s_id'];
        $result['as_start_time'] = time();
        $result['c_id'] = $data['c_id'];
        $result['as_type'] = $data['a_school_type'];
        $result['as_start_grade'] = YearToGrade($data['a_year'], $data['s_id']);

        if (!M('AuthSchool')->add($result)) {
            return 'error';
        }
    }

}
?>