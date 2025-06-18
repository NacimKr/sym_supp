<?php

namespace App\Controller\API;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class APIRecipeController extends AbstractController
{
    #[Route('/api/recipe', name: 'app_api_recipe')]
    public function index(RecipeRepository $recipeRepository): JsonResponse
    {
        return $this->json($recipeRepository->findAll(),200,[], ['groups' => ['read']]);
    }
}
