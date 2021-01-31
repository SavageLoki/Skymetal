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
     * @Route("/", name="homepage")
     */
        public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            ['isPublished' => true],
            ['publicationDate' => 'desc']
        );

        return $this->render('homepage/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("show/{id}", name="article_show")
     * @param Article $article
     * @return mixed
     */
    public function show(Article $article)
    {
        return $this->render('homepage/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {

        $this->denyAccessUnlessGranted("ROLE_BLOGGER");
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);







        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdate(new \DateTime());
            $user = $this->getUser();
            $article->setBlogger($user);
            $article->setPublicationDate(new \DateTime());

            if ($article->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va etre charger
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new Response('Article submitted');
        }

        return $this->render('homepage/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function edit(Article $article, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_BLOGGER");

        //$user = $this->getUser();
        //$author = $article->getBlogger();

        // Check if the user is the author
       // if ($user->getId() != $author->getId()) {
         //   return $this->redirectToRoute("error_403");
       // }
        $oldPicture = $article->getPicture();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdate(new \DateTime());

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            if ($article->getPicture() !== null && $article->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $fileName = uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            } else {
                $article->setPicture($oldPicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new Response('Modifications registered');
        }

        return $this->render('homepage/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/last", name="last_article")
     * @return Response
     */
    public function lastArticle () {
        $entityManager = $this->getDoctrine();
        $repository = $entityManager->getRepository(Article::class);
        $result = $repository->findBy(array(),array('id'=>'DESC'),1,0);

       // $result = $this->getDoctrine()->getRepository(Article::class)->findBy(['id' => 'DESC']);
      $id = $result[0]->getId();

        return $this->redirectToRoute('article_show', ['id' =>$id]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @return Response
     */
    public function dashboard() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            ['isPublished' => true],
            ['publicationDate' => 'desc']
        );

        return $this->render('homepage/dashboard.html.twig', ['articles' => $articles]);
    }

}
