<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use App\Http\Controllers\Controller;
use App\Models\admin\CompanyCategory;
use Illuminate\Http\Request;

class CompanyCategoryController extends BaseController
{

    public function __invoke()
    {
        $company_categories = CompanyCategory::query()->select(["name", "id"])->where('status','=',0)->get();
        return $this->sendResponse($company_categories, 'Business category retrieved successfully');
    }
}
