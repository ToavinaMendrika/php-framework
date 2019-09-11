<?php
namespace App\Controllers;

use Framework\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface as Request;

class PageController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('page/index');
    }
}
