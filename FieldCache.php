<?php

namespace common\library;

/**
 * @author Wen	Zi <wenziyelang@gmail.com>
 */
use Yii;
use yii\web\NotFoundHttpException;
use backend\models\Field;
use backend\models\Model;
use common\library\FieldAddForm;
use yii\caching\FileCache;

class FieldCache {

    function __construct() {
        $this->field = new Field();
        $this->FieldAddForm = new FieldAddForm();
        $this->filecache = new \yii\caching\FileCache();
        $this->filecache->directoryLevel = \Yii::$app->params['directoryLevel'];
        $this->filecache->cachePath = \Yii::$app->params['cachePath'];
    }

    /**
     * @param $modelid 模型表副表主键id
     * @param $location 属于哪个位置，1为基本信息，2为通用信息，3为详细信息，4为其它信息
     * @param $cachename 缓存名称，留空为'model'.$modelid.$location
     * @return boolean whether the value is successfully stored into cache
     */
    public function set($modelid, $location, $cachename = '') {
        $field_array = $this->getField($modelid, $location);
        if (empty($field_array)) {
            return FALSE;
        } else {
            $FieldAddFormHtml = '';
            foreach ($field_array as $key => $value) {
                $FieldAddFormHtml .= $this->FieldAddForm->$value['field_type']($value['field'], '', $value);
            }
            $cachename = empty($cachename) ? 'model' . $modelid . $location : $cachename;
            if ($this->filecache->get($cachename)) {
                $this->filecache->delete($cachename);
                return $this->filecache->set($cachename, $FieldAddFormHtml);
            } else {
                return $this->filecache->set($cachename, $FieldAddFormHtml);
            }
        }
    }

    public function get($modelid, $location, $cachename = '') {
        $cachename = empty($cachename) ? 'model' . $modelid . $location : $cachename;
        if ($this->filecache->get($cachename)) {
            return $this->filecache->get($cachename);
        } else {
            $status = $this->set($modelid, $location);
            if (FALSE == $status) {
                return FALSE;
            } else {
                return $this->filecache->get($cachename);
            }
        }
    }

    /**
     * 删除缓存的值与指定键
     * @param $modelid 模型表主键id
     * @param $location 属于哪个位置，1为基本信息，2为通用信息，3为详细信息，4为其它信息
     * @param $cachename 缓存名称，留空为'model'.$modelid.$location
     * @return boolean if no error happens during deletion
     */
    public function delete($modelid, $location, $cachename = '') {
        $cachename = empty($cachename) ? 'model' . $modelid . $location : $cachename;
        return $this->filecache->delete($cachename);
    }

    /**
     * @param $modelid 模型表副表主键id
     * @param $location 属于哪个位置，1为基本信息，2为通用信息，3为详细信息，4为其它信息
     * @param $cachename 缓存名称，留空为$modelid.$ArticleArray['id'].$location 
     * @param $ArticleArray 文章的数组数据
     * @return boolean whether the value is successfully stored into cache
     */
    public function setArticle($modelid, $location, $cachename = '', $ID) {
        $field_array = $this->getField($modelid, $location);
        $ArticleArray = $this->getArticleArray($modelid, $ID);
        if (empty($field_array)) {
            return FALSE;
        } else {
            $FieldAddFormHtml = '';
            foreach ($field_array as $key => $value) {
                $ArticleValue = $ArticleArray[$value['field']];
                $FieldAddFormHtml .= $this->FieldAddForm->$value['field_type']($value['field'], $ArticleValue, $value);
            }
            $cachename = empty($cachename) ? $modelid . $ArticleArray['id'] . $location : $cachename;
            if ($this->filecache->get($cachename)) {
                $this->filecache->delete($cachename);
                return $this->filecache->set($cachename, $FieldAddFormHtml);
            } else {
                return $this->filecache->set($cachename, $FieldAddFormHtml);
            }
        }
        return $FieldAddFormHtml;
    }

    public function getArticle($modelid, $location, $cachename = '', $ID) {
        $cachename = empty($cachename) ? $modelid . $ID . $location : $cachename;
        if ($this->filecache->get($cachename)) {
            return $this->filecache->get($cachename);
        } else {
            $ArticleArray = $this->getArticleArray($modelid, $ID);
            $status = $this->setArticle($modelid, $location, $set_cachename = '', $ID);
            if (FALSE == $status) {
                return FALSE;
            } else {
                return $this->filecache->get($cachename);
            }
        }
    }

    protected function getArticleArray($modelid, $ID) {
        $model_datas = Model::findOne($modelid); //根据modelid查找需要的主表名称和附表名称
        $tableprefix = Yii::$app->components['db']['tablePrefix']; //表前缀
        $model_infoname = $model_datas->table_name; //主表名称
        $model_subname = $model_datas->attached_name; //附表名称
        if (!empty($model_subname)) {
            $sql = "select a.*,b.* from " . $tableprefix . $model_infoname . " as a  inner join " . $tableprefix . $model_subname . " as b on a.id = b.id WHERE a.id = " . $ID;
        } else {
            $sql = "select * from " . $tableprefix . $model_infoname . " WHERE id = " . $ID;
        }
        $ArticleArray = Yii::$app->db->createCommand($sql)->queryOne();
        return $ArticleArray;
    }

    /**
     * 删除文章缓存的值与指定键
     * @param $modelid 模型表主键id
     * @param $location 属于哪个位置，1为基本信息，2为通用信息，3为详细信息，4为其它信息
     * @param $cachename 缓存名称，留空为$modelid.$ID.$location
     * @param $ArticleArray 文章的数组数据
     * @return boolean if no error happens during deletion
     */
    public function deleteArticle($modelid, $location, $cachename = '', $ID) {
        $cachename = empty($cachename) ? $modelid . $ID . $location : $cachename;
        return $this->filecache->delete($cachename);
    }

    private function getField($modelid, $location) {
        $cur_model = Model::findOne($modelid);
        if ($cur_model['shared_modelid']) {
            $shared_modelid = $cur_model['shared_modelid'];
        }
        $str = isset($shared_modelid) ? ',' . $shared_modelid : '';
        $sql = 'SELECT * FROM ' . Yii::$app->components['db']['tablePrefix'] . 'field where modelid in (' . $modelid . $str . ') and location=' . $location . ' and disabled !=1 and isomnipotent!=1  AND field not in ("id","inputtime","updatetime","url","listorder","status","template","username","userid","modelid","catid","univ_type","counts") order by listorder desc,fieldid asc';
        if (($model = Field::findBySql($sql)->asArray()->all()) !== null) {

            return $model;
        } else {
            return FALSE;
        }
    }

    function __get($key) {
        if (isset($this->$key)) {
            return($this->$key);
        } else {
            return(NULL);
        }
    }

    function __set($key, $value) {
        $this->$key = $value;
    }

}
