<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Mail;

/**
 * SearchMail represents the model behind the search form about `frontend\models\Mail`.
 */
class SearchMail extends Mail
{

    // This attribute will hold the values to filter our database data
    public $created_at_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['to', 'subject', 'text', 'created_at_range'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Mail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'text', $this->text]);

        // do we have values? if so, add a filter to our query
        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'mail.created_at', strtotime($start_date), strtotime($end_date)]);
        }

        return $dataProvider;
    }
}
