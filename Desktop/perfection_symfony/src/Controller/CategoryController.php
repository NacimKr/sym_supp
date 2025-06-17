<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name: 'category_')]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->categoryWithRecipe("riz");
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $category = new Category();
        $form_category = $this->createForm(CategoryTypeForm::class);
        $form_category->handleRequest($request);

        if($form_category->isSubmitted() && $form_category->isValid()){
            $data = $form_category->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', "Votre catégorie a bien été créé");
            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form_category->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $em, Category $category)
    {
        $form_category = $this->createForm(CategoryTypeForm::class, $category);
        $form_category->handleRequest($request);

        if($form_category->isSubmitted() && $form_category->isValid()){
            $em->persist($form_category->getData());
            $em->flush();
            // dd($form_category->getData());
            $this->addFlash('success', "Votre catégorie a bien été créé");
            return $this->redirectToRoute('category_list');
        }
        
        return $this->render('category/edit.html.twig', [
            'form' => $form_category->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(EntityManagerInterface $em, Category $category)
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');
    }
}
