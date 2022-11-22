<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{

    static function triNb($nb): array
    {
        $table = [];

        for($i = 0 ; $i < $nb ; $i++){
            $alea = rand(0,99);

            if(fmod($alea, 2) == 0){
                array_unshift($table,$alea);

            }else{
                $table[] = $alea;
            }
        }
        return $table;
    }


    static function stringTab($nb){
        $faker = Factory::create();
        $tab = [];
        for ($i = 0 ; $i < $nb ; $i++){
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

        $article = new Article();
        $article->setTitre('Mon article aléatoire');
        $article->setContenu('Nullam id dolor id nibh ultricies vehicula. Nullam quis risus eget.');
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

        return $this->render('article/affichage.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/article/show/{year}", name="showByYear")
     */
    public function showByYear($year, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Article::class);
        $article = $repository->findByYear($year);

        return $this->render('article/affichage.html.twig', [
            'article' => $article,
        ]);
    }


}
