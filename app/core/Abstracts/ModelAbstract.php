<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:11
 */

namespace App\Core\Abstracts;

use App\App;
use App\Core\Contracts\ModelContract;

abstract class ModelAbstract implements ModelContract
{
    protected $tableName = null;
    protected $pk = 'id';
    protected $hidden_fields = [];
    protected $guarded = [];

    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            if (is_string($key))
                $this->$key = $value;
        }
    }

    public static function all()
    {
        $tableName = self::getTableName();
        return App::getInstance()->db->select($tableName, '*');
    }

    public static function find($id)
    {
        $id = (int)$id;
        $tableName = self::getTableName();
        return App::getInstance()->db->get($tableName, '*', ['id' => $id]);
    }

    public static function create(array $data)
    {
        $class = get_called_class();
        $model = new $class();

        foreach ($data as $key => $value) {
            if (is_string($key))
                $model->$key = $value;
        }
        foreach ($model->guarded as $field) {
            unset($data[$field]);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return App::getInstance()->db->insert(self::getTableName(), $data);
    }

    /**
     * @return null|string|string[]
     */
    public static function getTableName()
    {
        $class = get_called_class();
        $obj = new $class();
        return $obj->tableName ?? pluralize(snake_case(basename($class)));
    }

}