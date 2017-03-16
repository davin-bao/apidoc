<?php
namespace ApiDoc;

/**
 * 路由
 * User: davin.bao
 * Date: 2017/3/15
 * Time: 17:26
 */
class Router
{
    private $routes = [];

    /**
     * 添加路由
     * @param $uri 路由地址
     * @param $callback 回调
     */
    public function add($uri, $callback){
        $this->routes[$uri] = $callback;
    }

    /**
     * 运行路由
     * @return mixed
     * @throws \Exception
     */
    public function run(){
        $uri = $_SERVER['REQUEST_URI'];
        foreach($this->routes as $key=>$value){
            if($key == $uri || $key == substr($uri, 1, strlen($uri) - 1)){
                return call_user_func($value, $_REQUEST);
            }
        }
        throw new \Exception('Page not found');
    }
}