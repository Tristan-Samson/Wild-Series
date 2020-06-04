<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use src\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
          ->getRepository(Program::class)
          ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", requirements={"slug"="[a-z0-9\-]*"}, name="wild_show")
     * @param string $slug
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }


    /**
     * @Route("/wild/category/{categoryName}", requirements={"categoryName"="[a-z0-9\-]*"}, name="wild_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category name has been sent to find a program in program\'s table.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );

        $category = $this->getdoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);
        
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findProgramInCategory($category);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No programs with '.$categoryName.' category, found in program\'s table.'
            );
        }

        return $this->render('wild/show_category.html.twig', [
            'programs' => $programs,
            'categoryName'  => $categoryName,
        ]);
    }
}