<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="app_categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }


    /**
     * @Route("/newcategorie", name="newcategorie")
     */

    public function newCategorie(EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $categorie->setNom('Relaxation');

        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->render('categorie/newcategorie.html.twig');
    }

}
