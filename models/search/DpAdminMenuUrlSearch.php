<?php

namespace wsl\rbac\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wsl\rbac\models\DpAdminMenuUrl;

/**
 * DpAdminMenuUrlSearch represents the model behind the search form about `\wsl\rbac\models\DpAdminMenuUrl`.
 */
class DpAdminMenuUrlSearch extends DpAdminMenuUrl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_id', 'enable_rule', 'status'], 'integer'],
            [['name', 'alias', 'route', 'method', 'host', 'note'], 'safe'],
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
     * @param string $formName the form name to be used for loading the data into the model.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = DpAdminMenuUrl::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'url_id' => $this->url_id,
            'enable_rule' => $this->enable_rule,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
