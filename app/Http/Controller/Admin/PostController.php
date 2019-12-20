<?php

namespace App\Http\Controller\Admin;

use App\Service\ApiService;
use App\Service\PostService;

class PostController extends ApiController
{
    protected function getService() : ApiService
    {
        return $this->container->get(PostService::class);
    }
}
