<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/20
 * Time: 9:42
 */

namespace App\Domain\Tokens;

interface ITokenRepository
{
    public function getComponentTicket();

    public function getAllAppConfig();

    public function getAppIdInfo($appId);
}
