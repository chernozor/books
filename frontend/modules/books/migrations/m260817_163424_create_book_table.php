<?php

namespace frontend\modules\books\migrations;

use Yii;
use Exception;
use yii\db\Migration;
use frontend\modules\books\models\Book;
use frontend\modules\books\models\Author;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m260817_163424_create_book_table extends Migration
{
    public string $bookTable = '{{%book}}';
    public string $bookAuthorTable = '{{%book_author}}';

    public function safeUp(): bool
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->schema->getTableSchema($this->bookTable) == null) {
            $this->createTable($this->bookTable, [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'description' => $this->string(2000),
                'year' => $this->integer(4)->notNull(),
                'isbn' => $this->string(13)->unique()->notNull(),
                'image_url' => $this->string(255)->notNull(),
                'created_at' => $this->integer(),
                'updated_at' => $this->integer(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
            ], $tableOptions);

            $this->createIndex('idx-book-name', $this->bookTable, 'name');
            $this->createIndex('idx-book-isbn', $this->bookTable, 'isbn');

            if ($this->db->schema->getTableSchema($this->bookAuthorTable) == null) {
                $this->createTable($this->bookAuthorTable, [
                    'book_id' => $this->integer()->notNull(),
                    'author_id' => $this->integer()->notNull(),
                ], $tableOptions);

                $this->addPrimaryKey('pk-book-author', $this->bookAuthorTable, ['book_id', 'author_id']);

                $this->addForeignKey(
                    'fk-books-author_id',
                    $this->bookAuthorTable,
                    'author_id',
                    '{{%author}}',
                    'id',
                    'CASCADE', // onDelete or set null/restrict
                    'CASCADE'  // onUpdate or set null/restrict
                );

                $this->addForeignKey(
                    'fk-books-book_id',
                    $this->bookAuthorTable,
                    'book_id',
                    '{{%book}}',
                    'id',
                    'CASCADE', // onDelete or set null
                    'CASCADE'  // onUpdate or set null
                );
            }

            if (YII_ENV_DEV) {
                for ($i = 1; $i <= 20; $i++) {
                    try {
                        $book = new Book();
                        $book->name = Yii::$app->getSecurity()->generateRandomString(10);
                        $book->description = Yii::$app->getSecurity()->generateRandomString(255);
                        $book->year = rand(2000, 2026);
                        $book->isbn = Yii::$app->getSecurity()->generateRandomString(13);

                        if ($book->save(false)) {
                            $count = rand(1, 2);
                            $author_first = Author::findOne(['id' => rand(1, 10)]);
                            $book->link('authors', $author_first);
                            if ($count === 2) {
                                $author_second = Author::findOne(['id' => rand(1, 10)]);
                                $book->link('authors', $author_second);
                            }
                        }
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

        $this->dropForeignKey('fk-books-book_id', $this->bookAuthorTable);
        $this->dropForeignKey('fk-books-author_id', $this->bookAuthorTable);

        if ($this->db->schema->getTableSchema($this->bookTable)) {
            $this->dropTable($this->bookTable);
        }

        if ($this->db->schema->getTableSchema($this->bookAuthorTable)) {
            $this->dropTable($this->bookAuthorTable);
        }

        return true;
    }
}
