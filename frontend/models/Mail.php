<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mail".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $to
 * @property string $subject
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 */
class Mail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'subject', 'text', 'to'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['to'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'to' => Yii::t('app', 'To'),
            'subject' => Yii::t('app', 'Subject'),
            'text' => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function sendMail()
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'mailCreate-html'],
                [
                    'text' => $this->text,
                    'to' => $this->to,
                ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->send();

    }
}
