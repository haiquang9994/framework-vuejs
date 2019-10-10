<?php

namespace App\Http\Controller\Admin;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class FinderController extends Controller
{
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
        // $contentType = $request->headers->get('Content-Type');

        // if (preg_match('/multipart\/form-data/', $contentType)) {
        //     return $request->request->get('command');
        // }
        // if (preg_match('/application\/x-www-form-urlencoded/', $contentType)) {
        //     dd($request);
        //     return $this->getJsonData('command');
        // }
        // return null;
    }

    protected function ___open_dir(Filesystem $filesystem)
    {
        $path = $this->request->request->get('path');
        $items = $filesystem->listContents($path);
        $data = [
            'files' => [],
            'folders' => [],
        ];
        foreach ($items as $item) {
            $type = $item['type'];
            if ($type === 'file') {
                $data['files'][] = [
                    'path' => './' . $item['path'],
                    'basename' => $item['basename'],
                    'extension' => $item['extension'],
                    'filename' => $item['filename'],
                    'url' => 'http://framework.lc/photos/' . $item['path'],
                    'thumb' => 'http://framework.lc/photos/' . $item['path'],
                ];
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
