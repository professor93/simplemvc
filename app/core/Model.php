<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:07
 */

namespace App\Core;


use App\App;
use App\Core\Abstracts\ModelAbstract;


class Model extends ModelAbstract
{

    /**
     * @override
     * @return array
     */
    public static function all()
    {
        $list = [];
        $res = parent::all();
        foreach ($res as $item) {
            $className = get_called_class();
            $list[] = new $className($item);
        }

        return $list;
    }

    public static function find($id)
    {
        $item = parent::find($id);
        $className = get_called_class();
        return new $className($item);
    }

    public function toArray()
    {
        $_hidden = ['pk', 'tableName', 'hidden_fields', 'guarded'];
        $arr = get_object_vars($this);
        $hidden = array_merge($this->hidden_fields, $_hidden);
        foreach ($hidden as $field) {
            unset($arr[$field]);
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function save()
    {
        if ($this->{$this->pk}) {
            $data = $this->toArray();
            unset($data[$this->pk]);

            App::getInstance()->db->update(self::getTableName(), $data, [
                $this->pk => $this->{$this->pk}
            ]);
        } else {
            $data = $this->toArray();
            self::create($data);
        }
    }

    public function delete()
    {
        if ($this->{$this->pk}) {
            App::getInstance()->db->delete(self::getTableName(), [
                $this->pk => $this->{$this->pk}
            ]);
        }
    }
}