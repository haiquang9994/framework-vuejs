<?php

namespace App\Http\Controller\Admin;

use App\Service\ApiService;
use App\Service\PostCategoryService;

class PostCategoryController extends ApiController
{
    protected function getService() : ApiService
    {
        return $this->container->get(PostCategoryService::class);
    }
}
