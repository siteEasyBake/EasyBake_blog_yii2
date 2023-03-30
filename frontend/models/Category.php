<?php

namespace app\models;
use yii\data\Pagination;
use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $title
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id']);
    }
    public function getArticlesCount()
    {
        return $this->getArticles()->count();
    }

    public static function getAll()
    {
        return Category::find()->all();
    }

    public static function getArticlesByCategory($id)
    {
        /// створити запит до БД для отримання всіх статей
        $query = Article::find()->where(['category_id'=>$id]);

        // отримати загальну кількість статей (але ще не отримати дані про статтю)
        $count = $query->count();

        // створити об'єкт розбиття на сторінки із загальною кількістю
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>6]);

        // обмежити запит за допомогою розбивки сторінок і отримати статті
        $articles = $query->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->all();

        $data['articles'] = $articles;
        $data['pagination'] = $pagination;
        
        return $data;
    }
}
