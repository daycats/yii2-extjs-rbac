<?php

namespace wsl\rbac\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wsl\rbac\models\DpAdminMenuUrlRule;

/**
 * DpAdminMenuUrlRuleSearch represents the model behind the search form about `\wsl\rbac\models\DpAdminMenuUrlRule`.
 */
class DpAdminMenuUrlRuleSearch extends DpAdminMenuUrlRule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule_id', 'url_id', 'status'], 'integer'],
            [['param_name', 'rule', 'note'], 'safe'],
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
        $query = DpAdminMenuUrlRule::find();

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
            'rule_id' => $this->rule_id,
            'url_id' => $this->url_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'param_name', $this->param_name])
            ->andFilterWhere(['like', 'rule', $this->rule])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
