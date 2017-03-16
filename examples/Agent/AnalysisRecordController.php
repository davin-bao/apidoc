<?php
namespace App\Modules\Api\v1\Controllers\Agent;

use App\Exceptions\NoticeMessageException;
use App\Models\DnsRecord;
use App\Modules\Api\v1\Controllers\Agent\Controller as BaseController;
use Illuminate\Http\Request;
use Swagger\Annotations as SWG;

/**
 * 域名解析记录管理
 * Class AnalysisRecordController
 * @package App\Modules\Api\v1\Controllers
 *
 * @author chuanhangyu
 * @since 2016/8/16 14:50
 *
 */
class AnalysisRecordController extends BaseController {

    /**
     * 查询域名解析记录
     *
     * @SWG\Get(
     *     path ="/agent/analysis-record/{id}",
     *     operationId="show",
     *     summary="查询域名解析记录",
     *     description="
    状态码 status 说明
常量 | 说明
----|----
STATUS_ENABLE | 启用状态
STATUS_DISABLE | 禁用状态
<br/>",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="id",
     *         description="代理商本地唯一域名解析记录ID",
     *         type="integer",
     *         in="path",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="得到域名解析记录",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"获取域名解析记录成功！","data":{"id":1,"record_type":"A",
     *                      "sub_domain":"www","record_value":"11.11.11.11","record_id":"2","mx":"1","ttl":"10",
     *                      "status":"STATUS_ENABLE"}}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="域名解析记录不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'获取失败，不存在此域名解析记录！'}"
     *         )
     *     )
     * )
     *
     * @param $id
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/15 18:02
     */
    public function show($id, Request $request) {
        //验证 id 必须为数字类型
        $request->merge(['id' => $id]);
        $this->validateRequest(config('rule.analysis_record.id'), $request);

        $agent_id = $request->header('agent-id');

        $result = $this->getService()->getAnalysisRecord($agent_id, $id);
        return $this->response($request, [
            'data' => $result,
            'msg' => '获取域名解析记录成功！'
        ]);
    }

    /**
     * 获取域名对应下所有的解析记录
     *
     * @SWG\Get(
     *     path ="/agent/analysis-record",
     *     operationId="index",
     *     summary="获取域名对应下所有的解析记录",
     *     description="
    状态码 status 说明
常量 | 说明
----|----
STATUS_ENABLE | 启用状态
STATUS_DISABLE | 禁用状态
<br/>",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="integer",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         description="返回记录的数量，默认返回20条",
     *         type="integer",
     *         in="query",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="offset",
     *         description="返回记录的开始位置，默认开始位置为1",
     *         type="integer",
     *         in="query",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="sort",
     *         description="返回记录的排序规则，如sort='id,name'，默认排序规则为主键字段",
     *         type="string",
     *         in="query",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="order",
     *         description="返回记录的排序方向(0 desc降序、1 asc升序)，如order='1,1'，默认排序方向为升序",
     *         type="string",
     *         in="query",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="得到所有的域名解析记录，不存在任何解析记录时，data字段为空数组",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"获取域名解析记录成功！","data":{{"id":1,"domain_id":"1","record_type":"A",
     *                      "sub_domain":"www","record_value":"ip:11.11.11.11","record_id":"2","mx":"1","ttl":"10",
     *                      "status":"STATUS_DISABLE","dns_line_id":"2"},{"id":1,"domain_id":"1","record_type":"A",
     *                      "sub_domain":"www","record_value":"ip:11.11.11.11","record_id":"2","mx":"1","ttl":"10",
     *                      "status":"STATUS_DISABLE","dns_line_id":"2"},{"id":1,"domain_id":"1","record_type":"A",
     *                      "sub_domain":"www","record_value":"ip:11.11.11.11","record_id":"2","mx":"1","ttl":"10",
     *                      "status":"STATUS_DISABLE","dns_line_id":"2"}}}
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/15 18:05
     */
    public function index(Request $request) {
        $this->validateRequest(config('rule.analysis_record.list'), $request, DnsRecord::$customAttributes);

        $request->merge([
            'agent_id' => (int)$request->header('agent-id')
        ]);
        $result = $this->getService()->getAnalysisRecordList($request->all());
        return $this->response($request,[
            'data' => $result
        ]);
    }

    /**
     * 添加域名解析记录
     *
     * @SWG\Post(
     *     path ="/agent/analysis-record",
     *     operationId="store",
     *     summary="添加域名解析记录",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="record_type",
     *         description="域名解析记录类型 (A,AAAA,CNAME,MX,NS,SRV,TXT)",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="record_value",
     *         description="解析内容",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="sub_domain",
     *         description="主机头",
     *         type="string",
     *         in="formData"
     *     ),
     *     @SWG\Parameter(
     *         name="dns_line_id",
     *         description="解析线路id
     * 通过接口/agent/analysis-record/line 获得dns_line_id",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="ttl",
     *         description="生存时间 (600-604800)",
     *         type="integer",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="mx",
     *         description="优先级 (1-20)",
     *         type="integer",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="添加域名解析记录成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"添加域名解析记录成功！","data":{"id":1}}
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/15 18:06
     */
    public function store(Request $request) {
        $this->validateRequest(DnsRecord::createRules(), $request, DnsRecord::$customAttributes);
        $request->merge([
            'agent_id' => (int)$request->header('agent-id')
        ]);
        $result = $this->getService()->addAnalysisRecord($request->all());
        return $this->response($request, [
            'data' => $result,
            'msg' =>  '添加此域名解析记录成功！'
        ]);
    }

    /**
     * 修改域名解析记录
     *
     * @SWG\Put(
     *     path ="/agent/analysis-record/{id}",
     *     operationId="update",
     *     summary="修改域名解析记录",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="id",
     *         description="解析记录ID",
     *         type="integer",
     *         in="path",
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
     *         name="sub_domain",
     *         description="主机头",
     *         type="string",
     *         in="formData"
     *     ),
     *     @SWG\Parameter(
     *         name="dns_line_id",
     *         description="解析记录线路id
     * 通过接口/agent/analysis-record/line 获得dns_line_id",
     *         type="string",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="record_type",
     *         description="域名解析记录类型 (A,AAAA,CNAME,MX,NS,SRV,TXT)",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="record_value",
     *         description="解析内容",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="ttl",
     *         description="生存时间 (600-604800)",
     *         type="integer",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="mx",
     *         description="优先级 (1-20)",
     *         type="integer",
     *         in="formData",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="修改域名解析记录成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"修改此域名解析记录成功！"}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="域名解析记录不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'修改失败，不存在此域名解析记录！'}"
     *         )
     *     )
     * )
     *
     * @param $id
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author chuanhangyu
     * @since 2016/8/16 15:10
     */
    public function update($id, Request $request) {
        $request->merge([
            'id' => $id,
            'agent_id' => (int)$request->header('agent-id')
        ]);
        $this->validateRequest(DnsRecord::updateRules(), $request, DnsRecord::$customAttributes);
        $this->getService()->updateAnalysisRecord($request->all());
        return $this->response($request, [
            'msg' => '更新此域名解析记录成功！'
        ]);
    }

    /**
     * 删除域名解析记录
     *
     * @SWG\Delete(
     *     path ="/agent/analysis-record/{id}",
     *     operationId="destroy",
     *     summary="删除域名解析记录",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="id",
     *         description="解析记录ID",
     *         type="integer",
     *         in="path",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="string",
     *         in="formData",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="删除解析记录成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"删除此域名解析记录成功！"}
     *         )
     *     ),
     *     @SWG\Response(
     *         response=409,
     *         description="解析记录不存在",
     *         @SWG\Schema(
     *             type="{'code':409,'msg':'删除失败，不存在此域名解析记录！'}"
     *         )
     *     )
     * )
     *
     * @param $id
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/15 18:09
     */
    public function destroy($id, Request $request) {
        //验证 id 必须为数字类型
        $request->merge(['id' => $id]);

        $this->validateRequest(config('rule.analysis_record.deletion'), $request, DnsRecord::$customAttributes);
        $parameters = array_merge($request->all(), [
            'id' => $id,
            'agent_id' => (int)$request->header('agent-id')
        ]);

        $this->getService()->deleteAnalysisRecord($parameters);
        return $this->response($request, [
            'msg' => '删除此域名解析记录成功！'
        ]);
    }

    /**
     * 获取该域名可用的解析线路
     *
     * @SWG\Get(
     *     path ="/agent/analysis-record/line",
     *     operationId="line",
     *     summary="获取该域名可用的解析线路",
     *     tags={"agent/analysis-record"},
     *     @SWG\Parameter(
     *         name="keyword",
     *         description="域名名称",
     *         type="string",
     *         in="query",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="获取域名解析可用线路成功",
     *         @SWG\Schema(
     *             type="array",
     *             example={"code":200,"msg":"获取域名解析可用线路成功！","data":{{"id":1,"name":"默认"},{"id":"2","name":"国内"},{"id":"3","name":"电信"},{"id":"3","name":"联通"},{"id":"4","name":"有道"}}}
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return $this|\Symfony\Component\HttpFoundation\JsonResponse
     *
     * @author qirun.huang
     * @since 2016/10/15 18:16
     */
    public function line(Request $request) {
        $this->validateRequest(config('rule.analysis_record.line'), $request, DnsRecord::$customAttributes);
        $result = $this->getService()->getAnalysisRecordLineList($request->header('agent-id'), $request->input('keyword', ''));
        return $this->response($request, [
            'data' => $result
        ]);
    }
}
