<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Provider\DateTime;
use Faker\Provider\Text;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{

    static function triNb($nb): array
    {
        $table = [];

        for ($i = 0; $i < $nb; $i++) {
            $alea = rand(0, 99);

            if (fmod($alea, 2) == 0) {
                array_unshift($table, $alea);

            } else {
                $table[] = $alea;
            }
        }
        return $table;
    }


    static function stringTab($nb)
    {
        $faker = Factory::create();
        $tab = [];
        for ($i = 0; $i < $nb; $i++) {
            $tab[] = $faker->word();
        }
        return $tab;
    }


    /**
     * @Route("/article", name="app_article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/article/{numero}", name="afficher_article")
     */

    public function afficher($numero): Response
    {
        return $this->render('article/article.html.twig', [
            'controller_name' => 'ArticleController',
            'numArticle' => $numero,
        ]);
    }

    /**
     * @Route("/tableau", name="app_tableau")
     */
    public function tableau(): Response
    {
        $tableTri = self::triNb(10);
        return $this->render('article/tableau.html.twig', [
            'controller_name' => 'ArticleController',
            'triNb' => $tableTri,
        ]);

        $stringTab = self::stringTab(10);
        return $this->render('article/tableau.html.twig', [
            'controller_name' => 'ArticleController',
            'stringTab' => $stringTab,
        ]);
    }

    /**
     * @Route("/article/{numero}/vote/{direction}")
     */

    public function vote($numero, $direction)
    {
        if ($direction === 'up') {
            $compteVote = rand(7, 99);
        } else {
            $compteVote = rand(0, 5);
        }

        return new JsonResponse(['votes' => $compteVote]);
    }

    /**
     * @Route("/newarticle", name="new")
     */

    public function new(EntityManagerInterface $entityManager)
    {
        $faker = Factory::create('fr_FR');
        $titre = $faker->words(3, true);
        $contenu = $faker->paragraph(6);

        $article = new Article();
        $article->setTitre($titre);
        $article->setContenu($contenu);
        $article->setDateCreation(DateTime::dateTime());
        //$article->setDateCreation(DateTime::dateTimeBetween('-1 years', '-11 months'));
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->render('article/newarticle.html.twig');
    }

    /**
     * @Route("/article/show/{id}", name="showById")
     */

    public function showById($id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Article::class);

        $article = $repository->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/show/select/{content}", name="showByContent")
     */
    public function showByContent($content, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Article::class);
        $articles = $repository->findByContent($content);

        return $this->render('article/showmulti.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/article/showyear/{year}", name="showByYear")
     */
    public function showByYear($year, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Article::class);
        $articles = $repository->findByYear($year);

        return $this->render('article/showmulti.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/article/afficher/{id}", name="afficherById")
     */
    public function afficherById(Article $article): Response
    {

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/{id}/voter", name="article_vote", methods="POST")
     */
    public function articleVote(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        $direction = $request->request->get('direction');

        if ($direction === 'up') {
        // $article->setVotes($article->getVotes() + 1);
            $article->upVote();
        } elseif ($direction === 'down') {
        // $article->setVotes($article->getVotes() - 1);
            $article->downVote();
        }
        $entityManager->flush();

        return $this->redirectToRoute('afficherById', [
            'id' => $article->getId()
        ]);


    }

}
