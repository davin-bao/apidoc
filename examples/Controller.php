<?php

namespace App\Modules\Api\v1\Controllers;

use Swagger\Annotations as SWG;

/**
 * API module Base Controller
 * Class Controller
 * @package App\Modules\APi\v1\Controllers
 *
 * @author davin.bao
 * @since 2016/7/19 9:34
 *
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host=API_HOST,
 *     basePath="api/v1",
 *     @SWG\Info(
 *         title="说明",
 *         description="
1.接入说明 <br/>&nbsp;&nbsp;请咨询本系统专员开设测试账号密码, 待测试通过后,将开设生产环境的账号密码 <br/>
<br/>
2.签名验证算法 <br/>
&nbsp;&nbsp;signature = ******* <br/>

变量名 | 说明 | 样例
----|----|---
$password  |  账号对应的密码  |  1a2c3d4e
$agent-id  |  代理商唯一ID  |   10086
PUBLIC_KEY  |  系统提供的公钥  |  XMISP_TEST
$timestamp  |  当前时间戳  |  1471577680
<br/>
3.公共参数设置 <br/>&nbsp;&nbsp;在进行测试的时候，需先对公共参数进行设置。<br/>
&nbsp;&nbsp;步骤：1. 点击右上角“设置公共参数”按钮 2. 在弹出框内，分别将agent-id、timestamp、signature三个参数的值填入输入框内并点击对应输入框下的“设置”按钮，
3个参数的说明请参考 “签名验证算法”。<br/>",
 *         version="1.0.0"
 *     ),
 *     produces={"application/json"},
 *     @SWG\Tag(
 *         name="panel/domain",
 *         description="控制面板-域名管理"
 *     ),
 *     @SWG\Tag(
 *         name="agent/analysis-record",
 *         description="代理商-域名解析记录管理"
 *     ),
 *     securityDefinitions={
 *         @SWG\SecurityScheme(
 *             securityDefinition="api_key1",
 *             type="apiKey",
 *             name="agent-id",
 *             in="header"
 *         ),
 *         @SWG\SecurityScheme(
 *             securityDefinition="api_key2",
 *             type="apiKey",
 *             name="timestamp",
 *             in="header"
 *         ),
 *         @SWG\SecurityScheme(
 *             securityDefinition="api_key3",
 *             type="apiKey",
 *             name="signature",
 *             in="header"
 *         )
 *     }
 * )
 */
abstract class Controller
{
    public function __construct() {
        parent::__construct();
    }

    public static function actionName() {
        return [];
    }
}
