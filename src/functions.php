<?php

if (! function_exists('view')) {
    /**
     * 渲染视图
     * @param $file
     * @param array $variables
     * @return string
     * @throws Exception
     */
    function view($file, $variables = array())
    {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $file;
        if(!file_exists($file)){
            throw new \Exception('View file "'.$file.'" is not exist');
        }
        extract($variables);

        ob_start();
        include $file;
        $renderedView = ob_get_clean();

        return $renderedView;
    }
}

if (! function_exists('config')) {
    /**
     * 获取配置
     * @param $path
     * @return mixed
     * @throws Exception
     */
    function config($path){
        if(!$path){
            throw new \Exception('Parameter "path" expect string type');
        }
        $pathList = explode('.', $path);
        if(count($pathList) <= 0){
            throw new \Exception('Parameter "path" expect string type with "." split');
        }
        $configFile = array_shift($pathList);
        $configFile = dirname(dirname(__FILE__)) . '/config/' . $configFile . '.php';
        $config = require($configFile);
        if(!is_array($config) || count($config) <= 0){
            throw new \Exception('Config file "'.$configFile.'" error, expect return array');
        }
        return array_get($config, implode('.', $pathList), '');
    }
}

if (! function_exists('array_get')) {
    /**
     * 使用 "." 读取多维数组
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return value($default);
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

if (! function_exists('dd')) {
    /**
     * 脚本调试方法
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());

        die(1);
    }
}