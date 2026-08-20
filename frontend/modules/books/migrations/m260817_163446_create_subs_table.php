<?php

namespace frontend\modules\books\migrations;

use Yii;
use Exception;
use yii\db\Migration;
use common\models\User;
use frontend\modules\books\models\Subs;

/**
 * Handles the creation of table `{{%subs}}`.
 */
class m260817_163446_create_subs_table extends Migration
{
    public string $subsTable = '{{%subs}}';

    public function safeUp(): bool
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $table = $this->db->schema->getTableSchema($this->subsTable);
        if ($table === null) {
            $this->createTable($this->subsTable, [
                'author_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->notNull(),
            ], $tableOptions);

            $this->addPrimaryKey('pk-subs-user_author', $this->subsTable, ['user_id', 'author_id']);

            $this->addForeignKey(
                'fk-subs-author_id',
                $this->subsTable,
                'author_id',
                '{{%author}}',
                'id',
                'CASCADE', // onDelete or set null/restrict
                'CASCADE'  // onUpdate or set null/restrict
            );

            $this->addForeignKey(
                'fk-subs-user_id',
                $this->subsTable,
                'author_id',
                '{{%user}}',
                'id',
                'CASCADE', // onDelete or set null/restrict
                'CASCADE'  // onUpdate or set null/restrict
            );
        }

        if (YII_ENV_DEV) {
            $client = User::findOne(['username' => 'sub_one']);
            if ($client) {
                for ($i = 1; $i <= 3; $i++) {
                    try {
                        $subs = new Subs();
                        $subs->author_id = rand(1, 10);
                        $subs->user_id = $client->id;
                        $subs->save();
                    } catch (Exception $e) {
                        echo $e->getMessage() . "\n";
                        continue;
                    }
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

        $this->dropForeignKey('fk-subs-author_id', $this->subsTable);
        $this->dropForeignKey('fk-subs-user_id', $this->subsTable);

        if ($this->db->schema->getTableSchema($this->subsTable)) {
            $this->dropTable($this->subsTable);
        }

        return true;
    }
}
