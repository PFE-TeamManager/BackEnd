<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends AbstractController
{
    public function index()
    {
        return new JsonResponse([
            'message' => "Index"
        ], 200);
    }
}
