<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
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

    /**
     * @Route("/wild/program/{slug}", requirements={"slug"="[a-z0-9\-]*"}, name="wild_program")
     * @param string $slug
     * @return Response
     */
    public function showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No program name has been sent to find a season in season\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getdoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $slug]);
            
        $seasons = $program->getSeasons();

        if (!$seasons) {
            throw $this->createNotFoundException(
                'No season with '.$slug.' program\'s name found in season\'s table.'
            );
        }

        return $this->render('wild/show_program.html.twig', [
            'seasons' => $seasons,
            'slug'  => $slug,
        ]);
    }

    /**
     * @Route("/wild/season/{id}", requirements={"id"="[0-9]*"}, name="wild_season")
     * @param int $id
     * @return Response
     */
    public function showBySeason(?int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No season has been sent to find an episode in episode\'s table.');
        }

        $season = $this->getdoctrine()
            ->getRepository(Season::class)
            ->find($id);

        $program = $season->getProgramId();
            
        $episodes = $season->getEpisodes();

        if (!$episodes) {
            throw $this->createNotFoundException(
                'No episodes fond in season '.$id.' in episode\'s table.'
            );
        }

        return $this->render('wild/show_season.html.twig', [
            'episodes' => $episodes,
            'season'  => $season,
            'program' => $program,
        ]);
    }

    /**
     * @Route("/wild/episode/{id}", requirements={"id"="[0-9]*"}, name="wild_episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeasonId();
        $program = $season->getProgramId();
        return $this->render('wild/show_episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
            ]);
    }
}