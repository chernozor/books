<?php

declare(strict_types=1);

namespace frontend\modules\books\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $year
 * @property string $isbn
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property array $bookAuthors
 * @property array $subs
 * @property array $authors
 */
class Book extends \yii\db\ActiveRecord
{
    const SCENARIO_BOOK_CREATE = 'book-create';
    const EVENT_BOOK_CREATE = 'bookCreateEvent';

    public $authorList;

    public static function tableName()
    {
        return 'book';
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
            ['isbn', 'match', 'pattern' => '/^\d{13}$/', 'message' => 'Ограничение в 13 цифр'],
            [['name', 'description'], 'string', 'max' => 5000],
            [['image_url',], 'string', 'max' => 255],
            [['authorList'], 'safe'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if ($this->scenario === self::SCENARIO_BOOK_CREATE) {
                $this->trigger(self::EVENT_BOOK_CREATE);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_BOOK_CREATE] = ['name', 'year', 'isbn'];
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
            'slug' => 'ЧПУ',
            'image_url' => 'Картинка',
        ];
    }

    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id'])->asArray();
    }


    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('bookAuthors');
    }

    public function getSubs(): array
    {
        $authors = array_column($this->bookAuthors, 'author_id');

        $users = Subs::find()->select('user_id')->where(['in', 'author_id', $authors])->asArray()->all();

        return ArrayHelper::map($users, 'user_id', 'user_id');
    }
}
