<?php

namespace App\Modules\Api\v1\Controllers\Agent;

use App\Modules\Api\v1\Controllers\Controller as BaseController;

/**
 * Class Controller
 * 代理商接口基类
 *
 * @package App\Modules\Api\v1\Controllers\Agent
 * @author davin.bao
 * @since 2016/9/20 9:34
 */
abstract class Controller extends BaseController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('api_agent_authenticated');
        $this->middleware('api_logger');
    }
}
