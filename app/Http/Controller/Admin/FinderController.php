<?php

namespace App\Http\Controller\Admin;

use Cocur\Slugify\Slugify;
use elFinderConnector;
use elFinder;

class FinderController extends Controller
{
    public function accessControl(string $attr, string $path)
    {
        return strpos(basename($path), '.') === 0
            ? !($attr == 'read' || $attr == 'write')
            : null;
    }

    private function getOptions()
    {
        return [
            'locale' => '',
            'roots'  => [
                [
                    'driver' => 'LocalFileSystem',
                    'path'   => ROOT_PATH . '/public/photos',
                    'URL'    => '/photos',
                    'accessControl' => [$this, 'accessControl']
                ]
            ],
        ];
    }

    public function connector()
    {
        $options = $this->getOptions();
        $connector = new elFinderConnector(new elFinder($options));
        $connector->run();
    }

    public function upload()
    {
        $file = $this->request->files->get('file');
        $filename = $this->container->get(Slugify::class)->slugify(substr($file->getClientOriginalName(), 0, strrpos($file->getClientOriginalName(), '.'))) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(ROOT_PATH . '/public/photos/upload', $filename);

        return $this->json([
            'name' => $filename,
            'status' => 'done',
            'thumbUrl' => 'photos/upload/' . $filename,
            'url' => 'photos/upload/' . $filename,
        ]);
    }
}
