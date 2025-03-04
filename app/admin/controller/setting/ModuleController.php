<?php


namespace app\admin\controller\setting;


use app\admin\service\setting\ModuleService;
use app\admin\validate\setting\ModuleValidate;
use DI\Annotation\Inject;
use nyuwa\NyuwaController;
use nyuwa\NyuwaResponse;
use support\Request;

class ModuleController extends NyuwaController
{

    /**
     * @Inject
     * @var ModuleService
     */
    protected ModuleService $service;


    /**
     * @Inject
     * @var ModuleValidate
     */
    protected ModuleValidate $validate;

    /**
     * 本地模块列表
     * @return NyuwaResponse
     */
    #[GetMapping("index"), Permission("setting:module:index")]
    public function index(Request $request): NyuwaResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }

    /**
     * 新增本地模块
     * @param Request $request
     * @return NyuwaResponse
     */
    #[PutMapping("save"), Permission("setting:module:save"), OperationLog]
    public function save(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("moduleCreate")->check($request->all());
        $this->service->createModule($data);
        return $this->success();
    }

    /**
     * 启停用模块
     * @param Request $request
     * @return NyuwaResponse
     */
    #[PostMapping("modifyStatus"), Permission("setting:module:status"), OperationLog]
    public function modifyStatus(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("moduleStatus")->check($request->all());
        return $this->service->modifyStatus($data) ? $this->success() : $this->error();
    }

    /**
     * 安装模块
     * @param string $name
     * @return NyuwaResponse
     */
    #[PutMapping("install/{name}"), Permission("setting:module:install"), OperationLog]
    public function install(string $name): NyuwaResponse
    {
        return $this->service->installModuleData($name) ? $this->success() : $this->error();
    }

    /**
     * 卸载删除模块
     * @param string $name
     * @return NyuwaResponse
     * @throws \Throwable
     */
    #[DeleteMapping("delete/{name}"), Permission("setting:module:delete"), OperationLog]
    public function delete(string $name): NyuwaResponse
    {
        return $this->service->uninstallModule($name) ? $this->success() : $this->error();
    }



}