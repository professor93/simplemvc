<?php

/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:57
 */

namespace App\Core;

use App\Core\Contracts\ConfigContract;
use App\Core\Contracts\SingletonContract;
use App\Core\Helpers\ArrayHelper;

class Config implements ConfigContract, SingletonContract
{

    private static $instance;
    private $repository;
    private $dottedRepository;

    public function __construct()
    {
        $this->repository = $this->collectConfigs();
        $this->dottedRepository = ArrayHelper::dot($this->repository);
    }

    /**
     * Get config value
     * @param string $key
     * @param string|array|null $default
     * @return string|array
     */
    public static function get(string $key, $default = null)
    {
        if ($key === '') {
            return $default;
        }

        $config = self::getInstance();

        if (isset($config->dottedRepository[$key]))
            return $config->dottedRepository[$key];
        $sfa = $config->searchFromArray($config->repository, $key);
        return $sfa ?? $default;
    }


    /**
     * Search for array values
     * @param $context
     * @param $name
     * @return mixed|null
     */
    private function searchFromArray(&$context, $name)
    {
        $pieces = explode('.', $name);
        foreach ($pieces as $piece) {
            if (!is_array($context) || !array_key_exists($piece, $context)) {
                return null;
            }
            $context = &$context[$piece];
        }
        return $context;
    }


    /**
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Collect all configs from config files
     * @return mixed
     */
    private function collectConfigs()
    {
        $configFiles = glob(CONFIG_PATH . '*.php');
        return array_reduce($configFiles, function ($result, $item) {
            $result[basename($item, '.php')] = include($item);
            return $result;
        });
    }
}