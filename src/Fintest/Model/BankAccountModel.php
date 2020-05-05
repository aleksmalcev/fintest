<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 01.05.2020
 * Time: 8:17
 */

namespace Fintest\Model;


class BankAccountModel
{
    public function getBalance($userId)
    {

    }

    /**
     * Withdraw money from the account
     *
     * @param $userId
     * @param $val
     */
    public function withdrawMoney($userId, $val)
    {

    }

    public function addMoney($userId, $val)
    {
        throw new \Exception('Method not supported');

    }
}