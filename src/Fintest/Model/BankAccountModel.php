<?php

namespace Fintest\Model;


use Fintest\Service\DbMng;
use Fintest\Service\ModelMng;


class BankAccountModel
{
    private function checkUserId($userId)
    {
        /** @var \Fintest\Model\UserModel $userModel */
        $userModel = ModelMng::getModel('User');
        if (! $userModel->isSetUserId($userId, true)) {
            $userId = $userId ?? 'null';
            throw new \Exception('Unknown user - '.$userId);
        }
    }

    public function getAccount($userId, $forUpdate = false)
    {
        $this->checkUserId($userId);
        $params =['#userId' => $userId];
        $query = 'SELECT * FROM accounts WHERE user_id = #userId';
        if ($forUpdate) {
            $query .= ' FOR UPDATE';
        }
        $res = DbMng::query($query, $params);
        if ($res === false) {
            $err = DbMng::error();
            throw new \Exception($err);
        }

        try {
            $numRows = \mysqli_num_rows($res);
            if ($numRows > 1) {
                $err = 'More then 1 results while getAccount';
                throw new \Exception($err);
            }

            if ($numRows <= 0) {
                $err = 'User don\'t has account';
                throw new \Exception($err);
            }

            $row = \mysqli_fetch_array($res);
            return $row;

        } finally  {
            \mysqli_free_result($res);
        }

    }

    public function getBalance($userId, $forUpdate = false)
    {
        $accountData = $this->getAccount($userId, $forUpdate);
        return $accountData['value'];
    }

    private function setBalance($userId, $val)
    {
        $this->checkUserId($userId);
        $params =['#userId' => $userId, '#value' => $val];
        $query = 'UPDATE accounts SET `value` = #value WHERE user_id = #userId';
        $res = DbMng::query($query, $params);
        if ($res === false) {
            $err = DbMng::error();
            throw new \Exception($err);
        }
    }

    /**
     * Debiting money from the account
     *
     * @param $userId
     * @param $val
     */
    public function debitingMoney($userId, $val)
    {
        DbMng::beginTransaction();
        try {
            // - check if money enough
            $balanceVal = $this->getBalance($userId, true);
            $newBalanceVal = $balanceVal - $val;
            if ($newBalanceVal < 0) {
                throw new \Exception('Request cancelled: Not enough money in the account');
            }
            // - debiting money
            $this->setBalance($userId, $newBalanceVal);
        } catch (\Throwable $e) {
            DbMng::rollbackTransaction();
        } finally {
            DbMng::commitTransaction();
        }
    }

    public function addMoney($userId, $val)
    {
        throw new \Exception('Method not supported');

    }
}