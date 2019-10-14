<?php

namespace App\Lib\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;
use Cocur\Slugify\Slugify;

class Local
{
    protected $adapter;
    protected $filesystem;
    protected $options;
    protected $slugify;

    public function __construct()
    {
        $this->options = $options = $this->getOpts();
        $this->adapter = $adapter = new LocalAdapter($options['root_files']);
        $this->filesystem = new Filesystem($adapter);
        $this->slugify = new Slugify();
    }

    protected function getOpts()
    {
        return [
            'static_url' => 'http://framework.lc/photos/',
            'root_files' => ROOT_PATH . '/public/photos',
        ];
    }

    protected function url(string $path)
    {
        return sprintf("%s/%s", rtrim($this->options['static_url'], '/'), trim($path, '/'));
    }

    protected function fullPath(string $path)
    {
        return sprintf("%s/%s", rtrim($this->options['root_files'], '/'), trim($path, '/'));
    }

    protected function get_parent_path(string $path): string
    {
        $list = explode('/', $path);
        array_pop($list);
        return implode('/', $list);
    }

    protected function root_tree(string $path): array
    {
        $data = [];
        $count = 0;
        while ($path !== '.') {
            $count++;
            $children = $data;
            $data = [];
            $parent_path = $this->get_parent_path($path);
            $items = $this->filesystem->listContents($parent_path);
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

    protected function file(string $basename, string $filename, string $path, string $ext, string $url, string $thumb)
    {
        return [
            'basename' => $basename,
            'filename' => $filename,
            'path' => $path,
            'url' => $url,
            'thumb' => $thumb,
        ];
    }

    protected function image(string $fullpath, string $basename, string $filename, string $path, string $ext, string $url, string $thumb)
    {
        $size = getimagesize($fullpath);
        return [
            'basename' => $basename,
            'filename' => $filename,
            'path' => $path,
            'url' => $url,
            'thumb' => $thumb,
            'size' => [
                'width' => $size[0],
                'height' => $size[1],
            ],
        ];
    }

    protected function isImage(string $fullpath)
    {
        $image_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $type = mime_content_type($fullpath);
        return in_array($type, $image_types);
    }

    public function listContents(string $path): array
    {
        $items = $this->filesystem->listContents($path);
        $data = [
            'files' => [],
            'folders' => [],
            'root' => $this->root_tree($path),
        ];
        usort($items, function ($a ,$b) {
            return $a['timestamp'] > $b['timestamp'] ? -1 : 1;
        });
        foreach ($items as $item) {
            $type = $item['type'];
            $fullpath = $this->fullPath($item['path']);
            if (strpos($item['basename'], '.') !== 0) {
                if ($type === 'file') {
                    if ($this->isImage($fullpath)) {
                        $data['files'][] = $this->image(
                            $fullpath,
                            $item['basename'], $item['filename'], './' . $item['path'],
                            $item['extension'], $this->options['static_url'] . $item['path'], $this->options['static_url'] . $item['path']
                        );
                    } else {
                        $data['files'][] = $this->file(
                            $item['basename'], $item['filename'], './' . $item['path'],
                            $item['extension'], $this->options['static_url'] . $item['path'], $this->options['static_url'] . $item['path']
                        );
                    }
                } elseif ($type === 'dir') {
                    $data['folders'][] = [
                        'path' => './' . $item['path'],
                        'basename' => $item['basename'],
                        'filename' => $item['filename'],
                    ];
                }
            }
        }
        return $data;
    }

    public function upload(string $path, array $files): array
    {
        $data = [];
        foreach ($files as $name => $file) {
            if ($file->isValid()) {
                $ext = $file->guessExtension();
                $client_original_name = $file->getClientOriginalName();
                $filename = $this->slugify->slugify(substr($client_original_name, 0, strrpos($client_original_name, '.'))) . '_' . substr(md5($name . time()), 0, 8);
                $basename = $filename . '.' . $ext;
                $filepath = trim($path, '/') . '/' . $basename;
                if ($this->filesystem->has($filepath)) {
                    $this->filesystem->delete($filepath);
                }
                $stream = fopen($file->getRealPath(), 'r+');
                $this->filesystem->writeStream($filepath, $stream);
                fclose($stream);
                $info = $this->filesystem->getMetadata($filepath);
                $data[] = [
                    'path' => './' . $info['path'],
                    'filename' => $filename,
                    'basename' => $basename,
                    'extension' => $ext,
                    'url' => $this->options['static_url'] . $info['path'],
                    'thumb' => $this->options['static_url'] . $info['path'],
                ];
            }
        }
        return $data;
    }
}
