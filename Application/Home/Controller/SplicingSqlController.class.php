<?php

namespace Home\Controller;

class SplicingSqlController extends BaseController
{
    /**
     * @var array database field
     */
    private $aFieldSelect = [
        'sProjectCode' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '项目编号'],
        'sProjectName' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '项目名称'],
        'sRoomTypeName' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '户型名称'],
        'sImageKey' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '户型图'],
        'sSalesArea' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '销售面积'],
        'sUseArea' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '使用面积'],
        'sGiveArea' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '赠送面积'],
        'sGiveContent' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '赠送内容'],
        'sGeneralComment' => ['type' => 'string', 'where' => "LIKE '%%%s%%'", 'fieldName' => '户型点评'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function splicing()
    {
//        var_dump(I('post.where'));die;
        if (I('post.where')) {
            $params = I('post.where');
            $this->sqlRule($params);
            var_dump(I('post.where'));
            die;
        }

        $aFieldSelect = $this->aFieldSelect;
        $aFieldSelect = json_encode($aFieldSelect);
        $this->assign('aFieldSelect', $aFieldSelect);
        $this->display();
    }

    public function sqlRule($params)
    {
        $sql = "";
        foreach ($params as $key => $val) {
            if (!$val['field']) {
                continue;
            }
            $sql .= sprintf(" and {$val['field']} {$this->aFieldSelect[$val['field']]['where']}", $val['value']);
        }
        var_dump($sql);
        die;
    }
}