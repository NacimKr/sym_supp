<?php

namespace App\Controller\Admin;

use App\Demo;
use App\Entity\Recipe;
use App\Form\RecipeTypeForm;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin', name:'admin.recipe.')]
final class MainController extends AbstractController
{
    #[Route('/demo', name:"demo")]
    public function demo(Demo $service_demo){
        dd($service_demo);
    }


    #[Route('/', name: 'home')]
    public function index(RecipeRepository $recipe_repository, EntityManagerInterface $em): Response
    {   
        // $recipe = new Recipe();
        // $recipe->setTitle("Barbe à papa")
        //     ->setContent("Lorem ipsum dolor sit amet")
        //     ->setSlug("barbe-a-papa")
        //     ->setCreateAt(new \DateTimeImmutable())
        //     ->setUpdatedAt(new \DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        $recipes = $recipe_repository->findAll();
        dump($recipe_repository->getSumDurationRecipe());
        return $this->render('main/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }


    #[Route('/recipe/{slug}-{id}', name: 'recipeshow', requirements:["slug" => "[a-zA-Z0-9-]+", "id" => "\d+"])]
    public function show(RecipeRepository $recipe_repository, string $slug ,string $id): Response
    {
        $recipe = $recipe_repository->find($id);

        //On vérifie si le slug est différent que celui qu'on a dans l'url
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute(
                "recipeshow", 
                ["slug" => $recipe->getSlug(), "id"=>$recipe->getId()]
            );
        }        
        return $this->render('main/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/recipe/{id}/edit', name:'recipe.edit', requirements:["id"=>Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(RecipeTypeForm::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('warning', "Votre recette a bien été modifiée");
            return $this->redirectToRoute('admin.recipe.home');
        }

        return $this->render('main/edit.html.twig',[
            "form" => $form->createView()
        ]);
    }

    #[Route('/recipe/add', name:'recipe.add')]
    public function add(Request $request, EntityManagerInterface $em):Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeTypeForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "Votre recette a bien été modifiée");
            return $this->redirectToRoute('admin.recipe.home');
        }

        return $this->render('main/add.html.twig',[
            "form" => $form->createView()
        ]);
    }

    #[Route('/recipe/{id}/delete', name:'recipe.delete', methods:['DELETE'], requirements:["id" => Requirement::DIGITS])]
    public function delete(Recipe $recipe, EntityManagerInterface $em){
        $em->remove($recipe);
        $em->flush();
        return $this->redirectToRoute("admin.recipe.home");
    }
}
