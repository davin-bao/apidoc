<?php
namespace ApiDoc;

use File;

class Application
{
    /**
     * 站点根地址
     * @return string
     */
    public static function getRootUrl(){
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }

    /**
     * 程序根目录
     * @return string
     */
    public static function getBaseDir(){
        return dirname(dirname(__FILE__));
    }

    public function run(){
        $rootUrl = self::getRootUrl();
        $router = new Router();
        $scanDirList = config('app.app_list');

        //配置路由
        foreach($scanDirList as $key=>$scanDir){
            //Doc 路由名称
            $docJsonRouteName = $key . '/docs.json';
            //Doc 存储路径
            $docJsonFile = $key . '.json';
            //Doc URL全路径
            $docUrl = $rootUrl . '/' . $docJsonRouteName;
            $docTitle = config('app.app_list.' . $key . '.title');

            $router->add($key, function($request) use ($docTitle, $docUrl){
                echo view('index.tm.php', ['docTitle' => $docTitle, 'urlToDocs' => $docUrl]);
            });

            $router->add($docJsonRouteName, function($request) use ($docJsonFile){
                echo $this->getJson($docJsonFile);
            });
        }
        //开始路由
        $router->run();
	}

    /**
     * 生成 doc 文件
     * @param $docJsonFile
     * @throws \Exception
     */
    public function getJson($docJsonFile){
        header("Content-type: application/json; charset=utf-8");

        $baseDir = self::getBaseDir();
        $appName = str_replace('.json', '', $docJsonFile);
        $docDir = $baseDir . DIRECTORY_SEPARATOR . 'documents';
        $filename = $docDir . DIRECTORY_SEPARATOR . $docJsonFile;

        if (config('app.generateAlways') || !file_exists($filename)){
            $apiHost =  config('app.app_list.' . $appName . '.api_host');
            $appDir =  config('app.app_list.' . $appName . '.scan_dir');
            $excludeDirs =  config('app.app_list.' . $appName . '.excludes');

            define("API_HOST",  $apiHost);

            if (!file_exists($appDir) || is_writable($docDir)) {
                //进行目录扫描
                $swagger =  \Swagger\scan($appDir, [
                    'exclude' => $excludeDirs
                ]);

                file_put_contents($filename, $swagger);
            }
        }

        echo file_get_contents($filename);
    }
}
?>
