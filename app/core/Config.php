<?php

use App\Core\Contracts\ConfigContract;
use App\Core\Contracts\SingletonContract;

/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:57
 */
class Config implements ConfigContract, SingletonContract
{

    private static $instance = null;
    private $repository;
    private $dottedRepository;

    public function __construct()
    {
        $this->repository = $this->collectConfigs();
        $this->dottedRepository = array_dot($this->repository);
    }

    /**
     * Get config value
     * @param string $key
     * @return string|array
     */
    public static function get(string $key, $default = null)
    {
        if (strlen($key) < 1)
            return $default;

        $config = Config::getInstance();

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
     * @return self
     */
    public static function getInstance()
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