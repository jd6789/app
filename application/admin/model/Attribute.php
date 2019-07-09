<?php

namespace app\admin\model;

use think\Model;

class Attribute extends Model
{
    //转换器,对字段值为数字的转换为文字输出 0 唯一属性 1 单选属性
    public function getAttrTypeAttr($value)
    {
        $attr_type = ['唯一属性','单选属性'];
        return $attr_type[$value];
    }

    public function getAttrInputTypeAttr($value)
    {
        $attr_input_type = ['输入框','下拉列表','多选框'];
        return $attr_input_type[$value];
    }
}
