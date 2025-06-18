<?php

namespace App\Controller;

use App\Demo;
use App\Entity\Category;
use App\Entity\Recipe;
use App\EventListener\ListenerHandListener;
use App\Form\RecipeTypeForm;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\String\Slugger\SluggerInterface;

final class RecipeController extends AbstractController
{
    #[Route('/demo', name:"demo")]
    public function demo(Demo $service_demo){
        dd($service_demo);
    }


    #[Route('/', name: 'home')]
    public function index(
        RecipeRepository $recipe_repository, 
        EntityManagerInterface $em,
        RecipeRepository $recipeRepository,
        CategoryRepository $categoryRepository
    ): Response
    {   
        // $recipe = new Recipe();
        // $recipe->setTitle("Barbe à papa")
        //     ->setContent("Lorem ipsum dolor sit amet")
        //     ->setSlug("barbe-a-papa")
        //     ->setCreateAt(new \DateTimeImmutable())
        //     ->setUpdatedAt(new \DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        // $myCategory = $categoryRepository->findOneBy(['libelle' => 'toto']);
        // $recipe = $recipeRepository->findOneBy(['title' => 'riz']);
        $recipes = $recipe_repository->recipeWithCategory();
        dump($recipes);
        dump($recipe_repository->recipeWithCategory()[0]->getCategory()->getRecipes());
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }


    #[Route('/recipe/{id}', name: 'recipe.show', requirements:['id' => Requirement::DIGITS])]
    public function show(RecipeRepository $recipe_repository, string $id): Response
    {
        $recipe = $recipe_repository->find($id);      
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }


    #[Route('/recipe/{id}/edit', name:'recipe.edit', requirements:["id"=>Requirement::DIGITS])]
    public function edit(
        Recipe $recipe, 
        Request $request, 
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory
    ):Response
    {
        $form = $this->createForm(RecipeTypeForm::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $recipe->setBrochureFilename($newFilename);
            }

            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', "Votre recette a bien été modifiée");
            return $this->redirectToRoute('home');
        }

        return $this->render('recipe/edit.html.twig',[
            "form" => $form->createView()
        ]);
    }


    #[Route('/recipe/add', name:'recipe.add', methods:['GET', 'POST'])]
    public function add(Request $request, 
        EntityManagerInterface $em, 
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory,
        EventDispatcherInterface $eventDispatcher
    ):Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeTypeForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

           $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                }
                $recipe->setBrochureFilename($newFilename);
            }

            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', "Votre recette a bien été modifiée");
            return $this->redirectToRoute('home');
        }

        return $this->render('recipe/add.html.twig',[
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
