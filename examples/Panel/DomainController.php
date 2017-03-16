<?php
namespace App\Modules\Api\v1\Controllers\Panel;

use App\Exceptions\NoticeMessageException;
use App\Models\Contact;
use App\Models\ContactTemplate;
use App\Models\Product;
use Illuminate\Http\Request;
use Swagger\Annotations as SWG;

/**
 * 域名管理
 * Class DomainController
 * @package App\Modules\Api\v1\Controllers\Panel
 *
 * @author cunqinghuang
 * @since 2016/9/23 16:30
 *
 */
class DomainController extends Controller {

    /**
     * 获取域名信息
     *
     * @SWG\Get(
     *     path ="/panel/domain/info",
     *     operationId="info",
     *     summary="获取域名信息",
     *     tags={"panel/domain"},
     *     @SWG\Parameter(
     *         name="token",
     *         description="通过 agent/auth/access-token 接口获取的 access token",
     *         type="string",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="string",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="得到对应的域名信息",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"获取域名信息成功！","data":{"id":1,"keyword":"xmisp.com","created_at":"2016-07-26 14:30:23","expire_at":"2018-07-26 14:30:23",
     *                      "status_list":{{"status":"ServerDeleteProhibited"},{"status":"clientDeleteProhibited"}},"dns_list":{{"dns":"f1g1ns1.dnspod.net"},{"dns":"f1g1ns2.dnspod.net"}},
     *                      "contact_id":"1","admin_contact_id":"2","finance_contact_id":"3","tech_contact_id":"4"}}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="域名信息不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'获取失败，不存在此域名信息！'}"
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/09 08:51
     */
    public function info(Request $request) {
        $result = $this->getService()->domainInfo($request->get('keyword', ''));
        return $this->response($request, [
            'data' => $result
        ]);
    }

    /**
     * 域名证件上传和更新
     *
     * @SWG\Post(
     *     path="/panel/domain/upload-material",
     *     operationId="uploadMaterial",
     *     summary="域名证件上传和更新",
     *     description="如果填写了id_type则id_code、id_img必填，若填写了org_type则org_code、org_img、org_proof_type必填",
     *     tags={"panel/domain"},
     *     @SWG\Parameter(
     *         name="token",
     *         description="通过 agent/auth/access-token 接口获取的 access token",
     *         type="string",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="registrant_type",
     *         description="注册人类型(E 组织、 I 个人)",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="id_type",
     *         description="身份证类型(SFZ 身份证、 HZ 护照、 JGZ 军官证、 QT 其他)",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="id_code",
     *         description="证件号码
     * 若填写id_type，则此项必填",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="id_img",
     *         description="身份证明材料
     * 若填写id_type，则此项必填
     * 支持格式：jpg、jpeg、png、gif、bmp",
     *         type="file",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="org_type",
     *         description="企业类型(PR 个体、 RU 工商)",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="org_code",
     *         description="组织证明编号
     * 若填写org_type，则此项必填",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="org_img",
     *         description="组织证明材料
     * 若填写org_type，则此项必填
     * 支持格式：jpg、jpeg、png、gif、bmp",
     *         type="file",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="org_proof_type",
     *         description="组织证明类型(YYZZ 营业执照、 ORG 组织机构代码证、 QT 其他)
     * 若填写org_type，则此项必填",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="bl_info",
     *         description="营业执照信息中的行政区划代码(最多50个字符)",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="上传证件成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"上传证件成功，正在审核中！"}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="域名不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'域名不存在，上传证件失败！'}"
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/09 18:38
     *
     *
     *     @HIDE\Parameter(
     *         name="user_region",
     *         description="用户类型(D 国内用户、F 海外用户)",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     */
    public function uploadMaterial(Request $request) {

        $product = Product::where('keyword', $request->input('keyword'))->where('status', '!=', Product::STATUS_DELETE)->first();
        if (!$product) {
            throw new NoticeMessageException('该域名存在！');
        }
        $contact = Contact::find($product->contact_id);
        if (!$contact) {
            throw new NoticeMessageException('该域名数据异常，域名所有者不存在！');
        }

        $uploadRules = ContactTemplate::uploadMaterialRules($contact->type, $request->input('id_type'), []);
        unset($uploadRules['id']);
        $this->validateRequest($uploadRules, $request, [
            'registrant_type' => '注册人类型',
            'user_region' => '用户类型',
            'id_type' => '证件类型',
            'id_code' => '证件号码',
            'id_img' => '身份证明材料',
            'org_type' => '企业类型',
            'org_code' => '组织证明编号',
            'org_img' => '组织证明材料',
            'org_proof_type' => '组织证明类型',
            'bl_info' => '行政区划代码'
        ]);

        $id_type = array_get($request, 'id_type', '');
        $id_img = array_get($request, 'id_img', '');
        $id_code = array_get($request, 'id_code', '');
        $org_type = array_get($request, 'org_type', '');
        $org_img = array_get($request, 'org_img', '');
        $org_code = array_get($request, 'org_code', '');
        $org_proof_type = array_get($request, 'org_proof_type', '');
        $keyword = $request->input('keyword');
        $token = $request->input('token');
        if (!$id_type && !$org_proof_type) {
            throw new NoticeMessageException('id_type 与 org_proof_type 至少填写一种', 409);
        }

        if ($id_type) {
            if (!$id_img) {
                throw new NoticeMessageException('id_img 不存在', 409);
            }
            if (!$id_code) {
                throw new NoticeMessageException('id_code 不存在', 409);
            }
        }

        if ($org_type) {
            if (!$org_img) {
                throw new NoticeMessageException('org_img 不存在', 409);
            }
            if (!$org_code) {
                throw new NoticeMessageException('org_code 不存在', 409);
            }
            if (!$org_proof_type) {
                throw new NoticeMessageException('org_proof_type 不存在', 409);
            }
        }
        $result = $this->getService()->uploadMaterial($request);
        return $this->response(
            $request,
            [
                'data' => $result
            ],
            '/panel/domain/upload-material?keyword='.$keyword.'&token='.$token
        );
    }

    /**
     * 更新DNS
     *
     * @SWG\Post(
     *     path="/panel/domain/update-dns",
     *     operationId="updateDns",
     *     summary="更新DNS",
     *     tags={"panel/domain"},
     *     @SWG\Parameter(
     *         name="token",
     *         description="通过 agent/auth/access-token 接口获取的 access token",
     *         type="string",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="dns",
     *         description="多个DNS请用,分割(默认采用系统缺省DNS)，第一个值为主DNS，第二个为辅DNS",
     *         type="string",
     *         items="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="更新DNS成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"更新DNS成功！"}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="此域名不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'更新DNS失败，此域名不存在！'}",
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/10 12:17
     */
    public function updateDns(Request $request) {
        $this->validateRequest(config('rule.product.dns-api'), $request, Product::$customAttributes);
        $parameters = $request->all();
        $dnsString = array_get($parameters, 'dns', '');
        $dns = explode(',', $dnsString);

        if($dns[count($dns)-1] == ""){
            $parameters['dns'] = substr($dnsString,0,strlen($dnsString)-1);
        }
        $result = $this->getService()->updateDns($parameters);
        return $this->response($request, [
            'data' => $result
        ]);
    }
}