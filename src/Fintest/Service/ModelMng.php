<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 05.05.2020
 * Time: 12:39
 */

namespace Fintest\Service;

use Fintest\App;


class ModelMng
{
    static private $models = [];

    /**
     * Get model by shortName.
     *
     * shortName example
     *     full name:   'Fintest\Model\UserModel'
     *     short name:  'User'
     *
     * @param $shortName
     * @return mixed
     */
    static public function getModel($shortName)
    {
        if (! isset(self::$models[$shortName])) {
            $modelClass = App::getRootModelNamespace().'\\'.$shortName.'Model';
            $m = new $modelClass();
            self::$models[$shortName] = $m;
        }
        return self::$models[$shortName];
    }

}