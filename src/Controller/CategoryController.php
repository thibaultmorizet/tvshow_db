<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/categories", name="category_list")
     */
    public function list()
    {
        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAllOrderedByLabel();

        return $this->render('category/list.html.twig', ["categories" => $categories]);
    }


    /**
     * @Route("/category/{id}", name="category_view", requirements={"id"="\d+"})
     */
    public function view($id)
    {
        /** @var CategoryRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findOneWithTvShows($id);
        
        return $this->render('category/view.html.twig', ["category" => $category]);
    }
}
