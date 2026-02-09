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

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\Driver;
use app\common\lists\ListsSearchInterface;


/**
 * Driver列表
 * Class DriverLists
 * @package app\adminapi\lists
 */
class DriverLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name', 'sex', 'passwrod', 'trans_company_id', 'status', 'Starting_price'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function lists(): array
    {
        return Driver::where($this->searchWhere)
            ->field('la_driver.*, la_transport_company.company_name as trans_company_name')
            ->limit($this->limitOffset, $this->limitLength)
            ->join('la_transport_company', 'la_driver.trans_company_id = la_transport_company.id', 'left')
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public function count(): int
    {
        return Driver::where($this->searchWhere)->count();
    }

}