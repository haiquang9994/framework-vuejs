<?php

namespace App\Http\Controller\Admin;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Cocur\Slugify\Slugify;

class FinderController extends Controller
{
    const ROOT = 'http://framework.lc/photos/';

    public function popup()
    {
        return $this->text('File Manager');
    }

    public function connector()
    {
        if ($command = $this->getCommand()) {
            $adapter = new Local(ROOT_PATH . '/public/photos');
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

    protected function ___upload(Filesystem $filesystem)
    {
        $path = $this->request->request->get('path');
        $files = $this->request->files->all();
        $data = [];
        foreach ($files as $name => $file) {
            if ($file->isValid()) {
                $ext = $file->guessExtension();
                $client_original_name = $file->getClientOriginalName();
                $filename = $this->container->get(Slugify::class)->slugify(substr($client_original_name, 0, strrpos($client_original_name, '.'))) . '_' . substr(md5($name . time()), 0, 8);
                $basename = $filename . '.' . $ext;
                $filepath = trim($path, '/') . '/' . $basename;
                if ($filesystem->has($filepath)) {
                    $filesystem->delete($filepath);
                }
                $stream = fopen($file->getRealPath(), 'r+');
                $filesystem->writeStream($filepath, $stream);
                fclose($stream);
                $info = $filesystem->getMetadata($filepath);
                $data[] = [
                    'path' => './' . $info['path'],
                    'filename' => $filename,
                    'basename' => $basename,
                    'extension' => $ext,
                    'url' => static::ROOT . $info['path'],
                    'thumb' => static::ROOT . $info['path'],
                ];
            }
        }
        return $this->json([
            'status' => true,
            'data' => $data,
        ]);
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

    protected function ___open_dir(Filesystem $filesystem)
    {
        $path = $this->request->request->get('path');
        $items = $filesystem->listContents($path);
        $data = [
            'files' => [],
            'folders' => [],
            'root' => $this->getRootTree($filesystem, $path),
        ];
        usort($items, function ($a ,$b) {
            return $a['timestamp'] > $b['timestamp'] ? -1 : 1;
        });
        foreach ($items as $item) {
            $type = $item['type'];
            if (strpos($item['basename'], '.') === 0) {
                break;
            }
            if ($type === 'file') {
                $data['files'][] = $this->file(
                    $item['basename'], $item['filename'], './' . $item['path'],
                    $item['extension'], static::ROOT . $item['path'], static::ROOT . $item['path']
                );
            } elseif ($type === 'dir') {
                $data['folders'][] = [
                    'path' => './' . $item['path'],
                    'basename' => $item['basename'],
                    'filename' => $item['filename'],
                ];
            }
        }
        return $this->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
