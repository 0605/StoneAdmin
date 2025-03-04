<?php

declare(strict_types=1);

namespace app\admin\model\system;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use nyuwa\NyuwaModel;
/**
 * 上传文件信息表
 * Class SystemUploadfile
 * @package app\admin\model\core
 *
 * @property int $id 主键
 * @property string $storage_mode 状态 (1 本地 2 阿里云 3 七牛云 4 腾讯云)
 * @property string $origin_name 原文件名
 * @property string $object_name 新文件名
 * @property string $mime_type 资源类型
 * @property string $storage_path 存储目录
 * @property string $suffix 文件后缀
 * @property int $size_byte 字节数
 * @property string $size_info 文件大小
 * @property string $url url地址
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property \Carbon\Carbon $created_at 创建时间
 * @property \Carbon\Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemUploadfile extends NyuwaModel
{
    use SoftDeletes;
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_uploadfile';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['storage_mode','origin_name','object_name','mime_type','storage_path','suffix','size_byte','size_info','url','created_by','updated_by','created_at','updated_at','deleted_at','remark',];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'string', 'created_by' => 'string', 'updated_by' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


    /* 一些关联表的关联操作 */



}
