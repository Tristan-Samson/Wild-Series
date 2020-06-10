<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityRepository;
use App\Controller\DefailtController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{

    /**
     * @Route("/category/", name="category_index")
     */
    public function index() : Response
    {
        $categories = $this->getDoctrine()
          ->getRepository(Category::class)
          ->findAll();
        
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, ['method' => Request::METHOD_POST]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}