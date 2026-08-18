<?php

namespace frontend\modules\books\migrations;

use Yii;
use Exception;
use yii\db\Migration;
use frontend\modules\books\models\Author;

class m260817_163419_create_author_table extends Migration
{
    public string $authorTable = '{{%author}}';

    public function safeUp(): bool
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $table = $this->db->schema->getTableSchema($this->authorTable);
        if ($table === null) {
            $this->createTable($this->authorTable, [
                'id' => $this->primaryKey(),
                'first_name' => $this->string(50),
                'middle_name' => $this->string(50),
                'last_name' => $this->string(50),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
            ], $tableOptions);

            $this->createIndex('idx-author-fio', $this->authorTable, ['first_name', 'middle_name', 'last_name']);
        }

        if (YII_ENV_DEV) {
            for ($i = 1; $i <= 10; $i++) {
                $author = new Author();
                $author->first_name = Yii::$app->getSecurity()->generateRandomString(10);
                $author->middle_name = Yii::$app->getSecurity()->generateRandomString(10);
                $author->last_name = Yii::$app->getSecurity()->generateRandomString(10);
                try {
                    $author->save(false);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    continue;
                }
            }
        }

        return true;
    }

    public function safeDown(): bool
    {
        if (YII_ENV_PROD) {
            return false;
        }

        if ($this->db->schema->getTableSchema($this->authorTable)) {
            $this->dropTable($this->authorTable);
        }

        return true;
    }
}
