<?php
/**
 * AdditionalValidationRulesBehavior
 *
 * jpn: 追加のバリデーションルール
 */
class AdditionalValidationRulesBehavior extends ModelBehavior {

    /**
     * setUp
     *
     * @param Model $model
     */
    public function setUp(Model $model, $config = array()){
    }

    /**
     * notEmptyWith
     * jpn: $withに指定されたフィールドに1つでも値が入っていたらnotEmptyを発動
     *
     */
    public function notEmptyWith(Model $model, $field, $with = array()){
        if (empty($with)) {
            return true;
        }
        $key = key($field);
        $value = array_shift($field);
        $v = new Validation();
        foreach ((array)$with as $withField) {
            if (!array_key_exists($withField, $model->data[$model->alias])) {
                continue;
            }
            if($v->notEmpty($model->data[$model->alias][$withField])) {
                return $v->notEmpty($value);
            }
        }
        return true;
    }

    /**
     * notEmptyWithout
     * jpn: $withoutに指定されたフィールドに1つも値が入っていなかったらnotEmptyを発動
     *
     */
    public function notEmptyWithout(Model $model, $field, $without = array()){
        $key = key($field);
        $value = array_shift($field);
        $v = new Validation();
        if (empty($without)) {
            return $v->notEmpty($value);
        }
        foreach ((array)$without as $withoutField) {
            if (!array_key_exists($withoutField, $model->data[$model->alias])) {
                continue;
            }
            if($v->notEmpty($model->data[$model->alias][$withoutField])) {
                return true;
            }
        }
        return $v->notEmpty($value);
    }

    /**
     * isUniqueWith
     * jpn: $withに指定されたフィールドとキーに$fieldの値がユニークかどうかをチェックする
     *
     */
    public function isUniqueWith(Model $model, $field, $with = array()){
        if (empty($with)) {
            return false;
        }
        $key = key($field);
        $value = array_shift($field);
        $fields = array(
            "{$model->alias}.{$key}" => $value,
        );
        foreach ((array)$with as $withField) {
            if (!array_key_exists($withField, $model->data[$model->alias])) {
                return false;
            }
            $withValue = $model->data[$model->alias][$withField];
            $fields["{$model->alias}.{$withField}"] = $withValue;
        }
        return !$model->find('count', array('conditions' => $fields, 'recursive' => -1));
    }

    /**
     * hiraganaAndSpace
     * jpn: 全角ひらがなと全角スペースのみ
     *
     */
    public function hiraganaAndSpace(Model $model, $field){
        $key = key($field);
        $value = array_shift($field);
        $field = array($key => str_replace('　','', $value));
        return $model->hiraganaOnly($field);
    }

    /**
     * katakanaAndSpace
     * jpn: 全角カタカナと全角スペースのみ
     *
     */
    public function katakanaAndSpace(Model $model, $field){
        $key = key($field);
        $value = array_shift($field);
        $field = array($key => str_replace('　','', $value));
        return $model->katakanaOnly($field);
    }

    /**
     * recordExists
     * jpn: belongsToなどで指定しているModelのレコード側が存在していること
     *
     * @param $arg
     */
    public function recordExists(Model $model, $field, $belongsToModelName){
        $key = key($field);
        $value = array_shift($field);
        if (is_array($belongsToModelName)) {
            $belongsToModelName = Inflector::classify(preg_replace('/_id$/', '', $key));
        }
        $belongsToModel = ClassRegistry::init($belongsToModelName);
        return $belongsToModel->exists($value);
    }

    /**
     * parentModelExists
     *
     */
    public function parentModelExists(Model $model, $field, $belongsToModelName){
        return $this->recordExists($model, $field, $belongsToModelName);
    }

    /**
     * inListFromConfigure
     * jpn: Configure::write()で設定されているarray()からinListを生成
     *
     */
    public function inListFromConfigure(Model $model, $field, $listname){
        $value = array_shift($field);
        $list = Configure::read($listname);
        if ($list !== array_values($list)) {
            // jpn: selectのoptionsにそのまま設置するような連想配列を想定
            $list = array_keys($list);
        }
        foreach ($list as $k => $v) {
            $list[$k] = (string)$v;
        }
        return Validation::inList($value, $list);
    }

    /**
     * formatJson
     * jpn: json形式の文字列かどうか
     *
     */
    public function formatJson(Model $model, $field){
        $value = array_shift($field);
        try {
            $result = json_decode($value);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * equalToField
     * jpn: 指定フィールドと同じ値(今のパスワードなどに使用)
     *
     * @param Model $model, $fiels, $current
     */
    public function equalToField(Model $model, $field, $current){
        $value = array_shift($field);
        if (empty($model->data[$model->alias][$model->primaryKey])) {
            return false;
        }
        $result = $model->find('count', array(
                'conditions' => array(
                    "{$model->alias}.{$model->primaryKey}" => $model->data[$model->alias][$model->primaryKey],
                    "{$model->alias}.{$current}" => $value,
                ),
            ));
        return ($result === 1);
    }

    /**
     * formatFuzzyEmail
     * jpn: 日本のキャリアの微妙なメールアドレスも通す
     *
     */
    public function formatFuzzyEmail(Model $model, $field){
        $value = array_shift($field);
        return preg_match('/^[-+.\w]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i', $value);
    }

    /**
     * allAllow
     * jpn: validation_patternでrequiredを作成するために使用
     *
     */
    public function allAllow(Model $model, $field){
        return true;
    }
}