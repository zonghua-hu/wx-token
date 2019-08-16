<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/8
 * Time: 17:06
 */
namespace App\Foundation\Repository;

use WecarSwoole\Repository\MySQLRepository;

class TokenRepository extends MySQLRepository
{
    protected function dbAlias(): string
    {
        return 'weicheche';
    }
    /**
     * @return mixed
     * @throws \Exception
     */
    public function getComponentTicket()
    {
        return $this->query
            ->select('component_verify_ticket')
            ->from('wei_wx_component')
            ->where(["info_type" => "component_verify_ticket"])
            ->orderBy("id desc")
            ->one();
    }
    /**
     * 获取全部商户信息
     * @return array|false
     * @throws \Exception
     */
    public function getAllAppConfig()
    {
        return $this->query
            ->select('*')
            ->from('wei_wx_merchant_info')
            ->where(['isvalid' => 1])
            ->list();
    }
    /**
     * 获取子商户信息
     * @param $appid
     * @return array|false
     * @throws \Exception
     */
    public function getAppIdInfo($appid)
    {
        return $this->query
            ->select('*')
            ->from('Wx_merchant_info')
            ->where(['isvalid' => 1])
            ->where(['app_id' => $appid])
            ->one();
    }
}
