<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
