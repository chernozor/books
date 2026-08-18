<?php

declare(strict_types=1);

namespace frontend\modules\books\models;

use Yii;
use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "author".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class Author extends \yii\db\ActiveRecord
{
    public mixed $bookCount;

    public static function tableName()
    {
        return 'author';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 50],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => Yii::t('app', 'Имя'),
            'last_name' => Yii::t('app', 'Фамилия'),
            'middle_name' => Yii::t('app', 'Отчество'),
        ];
    }

    public function getFio(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->via('bookAuthors');
    }

    public function getSubs(): ActiveQuery
    {
        return $this->hasMany(Subs::class, ['author_id' => 'id']);
    }

    public function getSubUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('subs');
    }

    public static function getAuthorList(): array
    {
        $authorList = Author::find()->all();
        if ($authorList) {
            return ArrayHelper::map($authorList, 'id', function ($item) {
                return implode(' ', [
                    'first_name' => $item->first_name,
                    'middle_name' => $item->middle_name,
                    'last_name' => $item->last_name,
                ]);
            });
        }

        return [];

    }

    public static function getTop($date = null): array
    {
        $query = Author::find()
            ->alias('author')
            ->select(['author.*', 'count(b.id) as bookCount'])
            ->joinWith('books b');

        if ($date) {
            $query = $query->where(['b.year' => $date]);
        } else {
            $query = $query->where(['b.year' => date('Y')]);
        }

        return $query->groupBy(['author.id'])
            ->orderBy(['bookCount' => SORT_DESC])
            ->limit(10)->all();
    }
}
