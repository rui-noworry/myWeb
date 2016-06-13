<?php
class GradeCourseModel extends CommonModel{

    public function getClassInfo($sId, $cType, $cGrade, $csType, $allSchool) {

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
        return $classInfo;
    }

}


?>

