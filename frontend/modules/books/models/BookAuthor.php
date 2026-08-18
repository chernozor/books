<?php

declare(strict_types=1);

namespace frontend\modules\books\models;


/**
 * This is the model class for table "book_author".
 *
 * @property integer $book_id
 * @property integer $author_id
 */
class BookAuthor extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%book_author}}';
    }

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'integer'],
        ];
    }

    public static function primaryKey()
    {
        return ['book_id', 'author_id'];
    }

    public function attributeLabels()
    {
        return [
            'book_id' => 'ID Книги',
            'author_id' => 'ID Автора',
        ];
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
