<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DataTableController extends AbstractController
{
    #[Route('/data/table', name: 'app_data_table')]
    public function index(): Response
    {
        return $this->render('data_table/index.html.twig', [
            'controller_name' => 'DataTableController',
        ]);
    }
}
