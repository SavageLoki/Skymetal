<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;

class HomepageController extends AbstractController
{
    /**
     * @Route("/homepage", name="homepage")
     */
        public function index(): Response
    {
        return $this->render("homepage/homepage.html.twig");
    }

    /**
     * @Route("/add", name="add")
     */
    public function add()
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        return $this->render('homepage/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
