<?php

namespace deka6pb\autoparser\components\Abstraction;

use yii\db\ActiveRecord;

class ATransactionModel extends ActiveRecord {

    const SCENARIO_INSERT = 'create';
    const SCENARIO_UPDATE = 'update';

    public function transactions() {
        return [
            self::SCENARIO_INSERT => self::OP_INSERT,
            self::SCENARIO_UPDATE => self::OP_UPDATE,
        ];
    }

    public function stopTransaction() {
        $transaction = self::getDb()->getTransaction();
        if ($transaction)
            $transaction->rollback();
    }
}