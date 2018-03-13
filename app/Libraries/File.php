<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

class File
{
    protected $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    protected $name_length = 7;


    /**
     * 保存图片
     * @param string $data
     * @return string
     */
    public function store($data = '')
    {
        return Storage::putFile($this->createName(), $data);
    }


    /**
     * 生成文件名称
     * @return string
     */
    protected function createName()
    {
        $name = '';

        for ($i = 0; $i < $this->name_length; $i++) {
            $name .= ($this->chars[ mt_rand(0, strlen($this->chars) - 1) ].time());
        }

        return $name;
    }



}
