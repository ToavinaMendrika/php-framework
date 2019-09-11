<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('blog/index',["variable"=>"test"]);
    }

    public function show(Request $request, int $id , string $slug)
    {
        
    }
}
