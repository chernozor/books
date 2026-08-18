<?php

namespace frontend\modules\books\migrations;

use Yii;
use Exception;
use yii\db\Migration;
use common\models\User;

class m260817_163399_generate_users extends Migration
{
    public string $userTable = '{{%user}}';

    public function safeUp(): bool
    {
        $table = $this->db->schema->getTableSchema($this->userTable);
        if ($table) {
            if (YII_ENV_DEV) {
                $time = time();
                $password = Yii::$app->security->generatePasswordHash('abc123456');

                $this->batchInsert('user', [
                    'username', 'email', 'password_hash', 'auth_key', 'created_at', 'updated_at',
                ], [
                    ['sub_one', 'sub1@example.com', $password, Yii::$app->security->generateRandomString(), $time, $time],
                    ['sub_two', 'sub2@example.com', $password, Yii::$app->security->generateRandomString(), $time, $time],
                    ['sub_three', 'sub3@example.com', $password, Yii::$app->security->generateRandomString(), $time, $time],
                ]);
            }
        }



        return true;
    }

    public function safeDown(): bool
    {
        if (YII_ENV_PROD) {
            return false;
        }

        if ($this->db->schema->getTableSchema($this->userTable)) {
            User::deleteAll();
        }

        return true;
    }
}
