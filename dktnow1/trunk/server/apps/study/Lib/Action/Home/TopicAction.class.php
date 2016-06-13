<?php

/**
 * TopicAction
 * 题目类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-16
 *
 */
class TopicAction extends BaseAction {

    // 由于题目更新和写入大部分雷同，因此用add方法来收集参数，根据bdStatus不同来辨别是写入还是更新
    public function add() {

        // 验证
        $data['co_id'] = intval($_POST['co_id']);
        $data['a_id'] = $this->authInfo['a_id'];
        $course = $this->checkOwner($data, 'Course');

        // 组织参数写入数据库
        $_POST['a_id'] = $data['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];

        // 判断传过来的数据是填空题还是简答题,1为简答题，2为填空题
        if (isset($_POST['type'])) {
            if ($_POST['type'] == 1) {
                $_POST['to_option'] = 0;
            }

            if (!is_array($_POST['to_answer'])) {
                $_POST['to_answer'] = array($_POST['to_answer']);
            }
            $_POST['to_answer'] = json_encode($_POST['to_answer']);

            if (get_magic_quotes_gpc()){
                $_POST['to_answer'] = addslashes($_POST['to_answer']);
            }

        }

        // 在写入或是更新题目时，需要把相关的学制、学期、版本、年级更新到topic_term表和topic_term_relation里
        $data = turnIdToWord($course, 'TopicTerm');

        // dbStatus为1是更新，2是写入
        if (intval($_POST['dbStatus']) == 1) {
            $this->update($data);
        } elseif (intval($_POST['dbStatus']) == 2) {
            $this->insert($data);
        }
    }

    // 添加题目
    public function insert($data = array()) {

        $result = $this->insertData();
        if (!$result) {
            $this->error('添加失败');
        }

        generationImg(C('TOPIC_TMP_PATH'), $result, '', htmlspecialchars_decode($_POST['to_title']));

        // 题目添加成功后，需要把题目id和题目标签添加到题目标签映射表
        if ($data) {
            $topicTermRelation = new TopicTermRelationAction();
            $_POST['to_id'] = $result;
            foreach ($data as $key => $value) {
                $_POST['tt_id'] = $value;
                $topicTermRelation->insert();
            }
        }

        $this->success($result);
    }

    // 编辑题目
    public function edit() {

        // 验证
        $data['co_id'] = intval($_POST['co_id']);
        $data['a_id'] = $this->authInfo['a_id'];
        $data['to_id'] = intval($_POST['to_id']);
        $topic = $this->checkOwner($data, 'Topic', 'to_id,to_title,to_type,to_option,to_answer');

        echo json_encode($topic);
    }

    // 更新题目
    public function update($data = array()) {
        $to_id = $_POST['to_id'];
        $result = $this->updateData();
        if (!$result) {
            $this->error('更新失败');
        }

        generationImg(C('TOPIC_TMP_PATH'), $to_id, '', htmlspecialchars_decode($_POST['to_title']));

        $this->success($to_id);
    }

    // 删除题目
    public function delete() {

        // 验证
        $data['co_id'] = intval($_POST['co_id']);
        $data['a_id'] = $this->authInfo['a_id'];
        $activity = $this->checkOwner($data, 'Activity');

        $data['to_id'] = intval($_POST['to_id']);

        $result = M('Topic')->where($data)->delete();
        if (!$result) {
            $this->error(M()->getLastSql());
        }

        // 删除成功后，还需要把相应的topic_term表的tt_count减1并且删除topic_term_realtion映射表相应的关系字段
        // 查出映射表中对应题库id
        $ttId = getValueByField(M('TopicTermRelation')->where($data)->field('tt_id')->select(), 'tt_id');

        // 删除映射表中此题目id
        M('TopicTermRelation')->where($data)->delete();

        // 更新，在题库表中把题目id对应的题库id的count使用量减1
        M('TopicTerm')->where(array('tt_id' => array('in', $ttId)))->save(array('tt_count' => array('exp', 'tt_count - 1')));

        $this->success('删除成功');
    }

    // 异步加载题目
    public function getEditTopic() {

        // 如果没有题目id，便直接返回
        if(strval($_POST['to_id']) == '') {
            exit;
        }

        // 查询该作业下相关的题目
        $data['to_id'] = array('IN', $_POST['to_id']);
        $topic = M('Topic')->where($data)->select();

        foreach ($topic as $key => &$value ) {
            if ($value['to_title']) {
                $value['to_title'] = stripslashes(stripslashes(htmlspecialchars_decode($value['to_title'])));
            }
        }

        echo json_encode($topic);

    }

    // 题库检索
    public function search() {

        // 如果仅仅是点击分页的话，就直接返回个默认的数据即可
        if (!$_POST['content'] && !$_POST['to_type'] && !$_POST['hotTag']) {
            $data = getListByPage('Topic', 'to_created DESC', array(), 5, TRUE, intval($_POST['p']));
        } else {

            // 如果在搜索栏里输入内容，以空格和加号分割，依据内容搜索题库表，再拿得到相关的tt_id去查题库关系表得到to_id
            if ($_POST['content']) {
                $content = split('[ +]', $_POST['content']);
                $condition = '';
                foreach ($content as $key => $value) {
                    $condition .= ' tt_title LIKE "' . '%' . $value . '%" OR';
                }
                $condition = substr($condition, 0, -2);
                $contentId = getValueByField(M()->query('SELECT tt_id FROM dkt_topic_term WHERE' . $condition), 'tt_id');
            }

            // 依据选中的标签在题库关系表查询与之相对应的to_id
            if ($_POST['hotTag']) {
                $tagId = explode(',', $_POST['hotTag']);
            }

            // 把得到tt_id合并
            $arr = array_unique(array_merge($contentId ? $contentId : array(), $tagId ? $tagId : array()));

            $res = getValueByField(M('TopicTermRelation')->where(array('tt_id' => array('in', $arr)))->field('to_id')->select(), 'to_id');

            $where = array();
            // 最后依据得到的to_id和选中的题型，查询相关的题目
            if (intval($_POST['to_type'])) {
                $where['to_type'] = intval($_POST['to_type']);
            }
            if ($res) {
                $where['to_id'] = array('in', $res);
            }

            if (!$where) {
                $data = array();
            } else {
                $data = getListByPage('Topic', 'to_created DESC', $where, 5, TRUE, intval($_POST['p']));
            }
        }

        foreach ($data['list'] as $key => &$value) {
            if ($value['to_title']) {
                $value['to_title'] = htmlspecialchars_decode($value['to_title']);
            }
        }

        echo json_encode($data);
    }
}