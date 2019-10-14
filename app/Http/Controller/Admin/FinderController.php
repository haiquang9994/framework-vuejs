<?php

namespace App\Http\Controller\Admin;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class FinderController extends Controller
{
    const STATIC_URL = 'http://framework.lc/photos/';
    const ROOT_FILES = ROOT_PATH . '/public/photos';

    protected function getOpts()
    {
        return [
            'static_url' => 'http://framework.lc/photos/',
            'root_files' => ROOT_PATH . '/public/photos',
        ];
    }

    public function popup()
    {
        return $this->text('File Manager');
    }

    public function connector()
    {
        if ($command = $this->getCommand()) {
            $path = $this->request->request->get('path');
            $filesystem = new \App\Lib\Filesystem\Local();
            if ($command === 'open_dir') {
                return $this->json([
                    'status' => true,
                    'data' => $filesystem->listContents($path),
                ]);
            } elseif ($command === 'upload') {
                $files = $this->request->files->all();
                return $this->json([
                    'status' => true,
                    'data' => $filesystem->upload($path, $files),
                ]);
            }
            $adapter = new Local(static::ROOT_FILES);
            $filesystem = new Filesystem($adapter);
            $method = "___$command";
            return $this->$method($filesystem);
        }
    }

    protected function getCommand()
    {
        $request = $this->request;

        return $request->request->get('command');
    }

    protected function getParentPath(string $path)
    {
        $list = explode('/', $path);
        array_pop($list);
        return implode('/', $list);
    }

    protected function getRootTree(Filesystem $filesystem, string $path)
    {
        $data = [];
        $count = 0;
        while ($path !== '.') {
            $count++;
            $children = $data;
            $data = [];
            $parent_path = $this->getParentPath($path);
            $items = $filesystem->listContents($parent_path);
            foreach ($items as $item) {
                if ($item['type'] === 'dir') {
                    $item_ = [
                        'path' => './' . $item['path'],
                        'basename' => $item['basename'],
                        'filename' => $item['filename'],
                    ];
                    if ($path === './' . $item['path']) {
                        $item_['children'] = $children;
                    }
                    $data[] = $item_;
                }
            }
            $path = $parent_path;
        }
        return $data;
    }

    protected function ___create_dir(Filesystem $filesystem)
    {
        $path = $this->request->request->get('path');
        $filesystem->createDir($path);
        return $this->json([
            'status' => true,
        ]);
    }

    protected function file(string $basename, string $filename, string $path, string $ext, string $url, string $thumb)
    {
        return [
            'extension' => $ext,
            'basename' => $basename,
            'filename' => $filename,
            'path' => $path,
            'url' => $url,
            'thumb' => $thumb,
        ];
    }
}
