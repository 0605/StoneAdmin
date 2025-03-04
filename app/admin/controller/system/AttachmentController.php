<?php


namespace app\admin\controller\system;


use app\admin\service\system\SystemUploadfileService;
use app\admin\validate\system\SystemUploadfileValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class AttachmentController extends NyuwaController
{

    /**
     * SystemUploadfileService 服务
     * @Inject
     * @var SystemUploadfileService
     */
    protected $service;


    /**
     * @Inject
     * @var SystemUploadfileValidate 验证器
     */
    protected $validate;

    /**
     * 列表数据
     */
    #[GetMapping("index"), Permission("system:attachment:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 回收站列表数据
     */
    #[GetMapping("recycle"), Permission("system:attachment:recycle")]
    public function recycle(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageListByRecycle($request->all()));
    }

    /**
     * 单个或批量删除附件
     */
    #[DeleteMapping("delete/{ids}"), Permission("system:attachment:delete")]
    public function delete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->delete($data['id']) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除文件 （清空回收站）
     */
    #[DeleteMapping("realDelete/{ids}"), Permission("system:attachment:realDelete"), OperationLog]
    public function realDelete(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->realDelete($data["id"]) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的文件
     */
    #[PutMapping("recovery/{ids}"), Permission("system:attachment:recovery")]
    public function recovery(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("key")->check($request->all());
        return $this->service->recovery($data["id"]) ? $this->success() : $this->error();
    }
}