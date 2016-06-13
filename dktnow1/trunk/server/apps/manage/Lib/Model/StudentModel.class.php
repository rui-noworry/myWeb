<?php
class StudentModel extends CommonModel {
    protected $tableName = 'auth';

    public function addClassStudent($a_id){

        $data['c_id'] = intval($_REQUEST['c_id']);
        $data['a_id'] = $a_id;
        $data['s_id'] = intval($_REQUEST['s_id']);
        $resutl = M('ClassStudent')->add($data);
    }

    public function addAuthSchool($a_id){
        $data['a_id'] = $a_id;
        $data['s_id'] = $_REQUEST['s_id'];
        $data['as_start_time'] = time();
        $data['c_id'] = $_REQUEST['c_id'];
        $data['as_type'] = $_REQUEST['c_type'];
        $data['as_start_grade'] = $_REQUEST['c_grade'];
        $result = M('AuthSchool')->add($data);
    }

    public function getClassInfo($sId, $cId, $cType, $ma_id, $cGrade, $csType, $allSchool){


        // 得到学制
        $types = $allSchool[$sId][s_type];
        $types = explode(',', $types);
        foreach ($csType as $csKey => $csVa){
            if (!in_array($csKey, $types)) {
                unset($csType[$csKey]);
            }
        }

        $classInfo['csType'] = $csType;


        // 得到年级
        $grade = C('GRADE_TYPE');
        $grade = $grade[$cType];

        $classInfo['grade'] = $grade;

        // 得到班级，以供显示上次的选择
        $whereClassShow['s_id'] = $sId;
        $whereClassShow['c_type'] = $cType;
        $whereClassShow['ma_id'] = $ma_id;
        $whereClassShow['c_grade'] = gradeToYear($cGrade, $sId);
        $class = M('Class')->where($whereClassShow)->getField('c_id, c_title');
        $classInfo['class'] = $class;


        return $classInfo;

    }


    public function getStudentInfo($aId){
        $vo = D('Student')->find($aId);
        $vo['a_region2'] = '"' . str_replace('###', '","', $vo['a_region']) . '"';
        $vo['a_birthday'] = date('Y-m-d',$vo['a_birthday']);

        return $vo;
    }


}