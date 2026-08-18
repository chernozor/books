<?php

declare(strict_types=1);

namespace frontend\modules\books\models;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $user_id
 * @property int $author_id
 *
 */
class Subs extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%subs}}';
    }

    public function rules(): array
    {
        return [
            [['author_id', 'user_id'], 'integer'],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
