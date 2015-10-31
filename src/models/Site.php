<?php

namespace canis\sensorHub\models;

use Yii;

/**
 * This is the model class for table "site".
 *
 * @property string $id
 * @property string $source_id
 * @property string $system_id
 * @property string $name
 * @property resource $data
 * @property integer $active
 * @property string $created
 * @property string $modified
 *
 * @property Source $source
 * @property Registry $id0
 */
class Site extends \canis\db\ActiveRecordRegistry
{
    protected $_dataObject;
    
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_VALIDATE, [$this, 'serializeData']);
    }
    
    /**
     * @inheritdoc
     */
    public static function isAccessControlled()
    {
        return false;
    }

     /**
     * [[@doctodo method_description:serializeAction]].
     */
    public function serializeData()
    {
        if (isset($this->_dataObject)) {
            try {
                $this->data = serialize($this->_dataObject);
            } catch (\Exception $e) {
                \d($this->_dataObject);
                exit;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_id', 'system_id', 'name'], 'required'],
            [['data'], 'string'],
            [['active'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['id', 'source_id'], 'string', 'max' => 36],
            [['system_id', 'name'], 'string', 'max' => 255],
            // [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['source_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source_id' => 'Source ID',
            'system_id' => 'System ID',
            'name' => 'Name',
            'data' => 'Data',
            'active' => 'Active',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id' => 'source_id']);
    }


    public function getDataObject()
    {
        if (!isset($this->_dataObject) && !empty($this->data)) {
            $this->_dataObject = unserialize($this->data);
            $this->_dataObject->model = $this;
        }
        return $this->_dataObject;
    }

    /**
     * Set action object.
     *
     * @param [[@doctodo param_type:ao]] $ao [[@doctodo param_description:ao]]
     */
    public function setDataObject($do)
    {
        $do->model = $this;
        $this->_dataObject = $do;
    }
}