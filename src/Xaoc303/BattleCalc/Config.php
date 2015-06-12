<?php namespace Xaoc303\BattleCalc;

class Config
{
    public static function get($key, $default = null)
    {
        if (empty($key)) {
            return $default;
        }

        $key = str_replace("battle-calc::", "", $key);
        $keys = explode('.', $key);

        $pathToDirConfigs = self::getPathToDirConfigs();
        $fullPathToFileConfig = self::getPathToFileConfig($pathToDirConfigs, $keys);

        if (!file_exists( $fullPathToFileConfig )) {
            return $default;
        }

        return self::getArrayFromFile($fullPathToFileConfig, $keys);
    }

    private static function getPathToDirConfigs()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    }

    private static function getPathToFileConfig($pathToDirConfigs, $keys)
    {
        return $pathToDirConfigs. $keys[0]. '.php';
    }

    private static function getArrayFromFile($file, $keys)
    {
        $get = function() use ($file) {
            return include $file;
        };
        $return = $get();
        unset($keys[0]);

        foreach ($keys as $v) {
            if (isset($return[$v])) {
                $return = $return[$v];
            }
        }
        return $return;
    }
}