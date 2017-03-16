<?php
namespace App\Modules\Api\v1\Controllers\Panel;

use App\Modules\Api\v1\Controllers\Controller as BaseController;

/**
 * Class Controller
 * 域名控制面板接口基类
 *
 * @package App\Modules\Api\v1\Controllers\Panel
 * @author davin.bao
 * @since 2016/9/20 9:34
 */
abstract class Controller extends BaseController
{
    public function __construct() {
        parent::__construct();
    }
}
