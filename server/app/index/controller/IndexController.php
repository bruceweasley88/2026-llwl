<?php

namespace app\index\controller;

use app\BaseController;
use app\common\service\JsonService;
use think\facade\Request;

class IndexController extends BaseController
{

    /**
     * @notes 主页
     * @param string $name
     * @return \think\response\Json|\think\response\View
     * @author 段誉
     * @date 2022/10/27 18:12
     */
    public function index($name = '你好')
    {
        $template = app()->getRootPath() . 'public/admin/index.html';
        if (Request::isMobile()) {
            $template = app()->getRootPath() . 'public/admin/index.html';
        }
        if (file_exists($template)) {
            return view($template);
        }
        return JsonService::success($name);
    }

    /**
     * @notes 图片上传
     * @return \think\response\Json
     * @author 段誉
     * @date 2026/01/12
     */
    public function upload()
    {
        $file = Request::file('file');
        if (!$file) {
            return JsonService::fail('请选择上传文件');
        }

        try {
            // 保存到 public/img 目录
            $savePath = app()->getRootPath() . 'public' . DIRECTORY_SEPARATOR . 'img';
            $info = $file->move($savePath);

            if ($info) {
                $filename = '/img/' . $info->getFilename();
                return JsonService::success('上传成功', [$filename]);
            } else {
                return JsonService::fail('上传失败');
            }
        } catch (\Exception $e) {
            return JsonService::fail('上传失败：' . $e->getMessage());
        }
    }


}
