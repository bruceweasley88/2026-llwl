<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * Driver验证器
 * Class DriverValidate
 * @package app\adminapi\validate
 */
class DriverValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'sex' => 'require',
        'passwrod' => 'require',
        'trans_company_id' => 'require',
        'status' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'name' => '名字',
        'sex' => '性别',
        'passwrod' => '密码',
        'trans_company_id' => '运输公司',
        'status' => '状态',
    ];


    /**
     * @notes 添加场景
     * @return DriverValidate
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function sceneAdd()
    {
        return $this->only(['name','sex','passwrod','trans_company_id','status','Starting_price']);
    }


    /**
     * @notes 编辑场景
     * @return DriverValidate
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function sceneEdit()
    {
        return $this->only(['id','name','sex','passwrod','trans_company_id','status','Starting_price']);
    }


    /**
     * @notes 删除场景
     * @return DriverValidate
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return DriverValidate
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}