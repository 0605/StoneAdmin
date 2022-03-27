<?php

declare(strict_types=1);

namespace app\admin\service\core;


use app\admin\mapper\core\SystemMenuMapper;
use DI\Annotation\Inject;
use nyuwa\abstracts\AbstractService;

class SystemMenuService extends AbstractService
{
    /**
     * @Inject
     * @var SystemMenuMapper
     */
    public $mapper;


    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeList(?array $params = null): array
    {
        $select = "id,parent_id,level,name as title,icon,redirect as href,type,open_type as openType";
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc','select' =>$select], $params);
        return parent::getTreeList($params);
    }

    /**
     * @param array|null $params
     * @return array
     */
    public function getTreeListByRecycle(?array $params = null): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'asc'], $params);
        return parent::getTreeListByRecycle($params);
    }

    /**
     * ��ȡǰ��ѡ����
     * @return array
     */
    public function getSelectTree(): array
    {
        return $this->mapper->getSelectTree();
    }

    /**
     * ͨ��code��ȡ�˵�����
     * @param string $code
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findNameByCode(string $code): string
    {
        if (strlen($code) < 1) {
            return nyuwa_trans('system.undefined_menu');
        }
        $name = $this->mapper->findNameByCode($code);
        return $name ?? nyuwa_trans('system.undefined_menu');
    }

    /**
     * �����˵�
     * @param array $data
     * @return string
     */
    public function save(array $data): string
    {
        $id = $this->mapper->save($this->handleData($data));

        // ����RESTFUL��ť�˵�
//        if ($data['type'] == SystemMenu::MENUS_LIST && $data['restful'] == '0') {
//            $model = $this->mapper->model::find($id, ['id', 'name', 'code']);
//            $this->genButtonMenu($model);
//        }

        return $id;
    }


    /**
     * ���ɰ�ť�˵�
     * @param SystemMenu $model
     * @return bool
     */
    public function genButtonMenu(SystemMenu $model): bool
    {
        $buttonMenus = [
            ['name' => $model->name.'�б�', 'code' => $model->code.':index'],
            ['name' => $model->name.'����վ', 'code' => $model->code.':recycle'],
            ['name' => $model->name.'����', 'code' => $model->code.':save'],
            ['name' => $model->name.'����', 'code' => $model->code.':update'],
            ['name' => $model->name.'ɾ��', 'code' => $model->code.':delete'],
            ['name' => $model->name.'��ȡ', 'code' => $model->code.':read'],
            ['name' => $model->name.'�ָ�', 'code' => $model->code.':recovery'],
            ['name' => $model->name.'��ʵɾ��', 'code' => $model->code.':realDelete'],
            ['name' => $model->name.'����', 'code' => $model->code.':import'],
            ['name' => $model->name.'����', 'code' => $model->code.':export']
        ];

        foreach ($buttonMenus as $button) {
            $this->save(
                array_merge(
                    ['parent_id' => $model->id, 'type' => SystemMenu::BUTTON],
                    $button
                )
            );
        }

        return true;
    }

    /**
     * ���²˵�
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data): bool
    {
        return $this->mapper->update($id, $this->handleData($data));
    }

    /**
     * ��������
     * @param $data
     * @return mixed
     */
    protected function handleData($data) {
        if ($data['parent_id'] == 0) {
            $data['level'] = '0';
            $data['type'] = SystemMenu::DIRECTORY_LIST;
        } else {
            if (is_array($data['parent_id'])) {
                $data['parent_id'] = array_pop($data['parent_id']);
            }
            $parentMenu = $this->mapper->read((string)$data['parent_id']);
            $data['level'] = $parentMenu['level'] . ',' . $parentMenu['id'];
        }
        return $data;
    }

    /**
     * ��ʵɾ���˵�
     * @param string $ids
     * @return array
     */
    public function realDel(string $ids): ?array
    {
        $ids = explode(',', $ids);
        // �����Ĳ˵�
        $ctuIds = [];
        if (count($ids)) foreach ($ids as $id) {
            if (!$this->checkChildrenExists( (int) $id)) {
                $this->mapper->realDelete([$id]);
            } else {
                array_push($ctuIds, $id);
            }
        }
        return count($ctuIds) ? $this->mapper->getMenuName($ctuIds) : null;
    }

    /**
     * ����Ӳ˵��Ƿ����
     * @param int $id
     * @return bool
     */
    public function checkChildrenExists(int $id): bool
    {
        return $this->mapper->checkChildrenExists($id);
    }

}
