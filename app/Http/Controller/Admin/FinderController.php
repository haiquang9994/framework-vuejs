<?php

namespace App\Http\Controller\Admin;

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
}
