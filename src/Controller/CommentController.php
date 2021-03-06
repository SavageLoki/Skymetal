<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/comment")
 */
//rajouter ici un id qui correspond à l'article qui contient les commentaires.
class CommentController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="comment_index", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @param Comment $comment
     * @return Response
     */
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll(['date_comment' => 'desc']);

        return $this->render('comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/article/{id}", name="comment_byarticle", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @param Article $article
     * @return Response
     */
    public function commentByBlog(CommentRepository $commentRepository, Article $article): Response
    {
        $comments = $commentRepository->findCommentsByBlog($article);

        return $this->render('comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/new/{id}", name="comment_new")
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function new(Request $request, Article $article): Response
    {

        $user = $this->security->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('comment_new', array('id'=>$article->getId())),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setAuthor($user);
            $comment->setDateComment(new \DateTime());

            //Lie l'article au commentaire
            $comment->setArticle($article);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('article_show', ['id'=>$article->getId()]);

        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     * @param Comment $comment
     * @return Response
     */

    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BLOGGER');
       // $user = $this->getUser();
       // $author = $comment->getAuthor();

        // Check if the user is the author
      //  if ($user->getId() != $author->getId()) {
        //    return $this->redirectToRoute("error_403");
        //}

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="comment_delete", methods={"DELETE"})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function delete(Request $request, Comment $comment): Response
    {

       // $user = $this->getUser();
       // $author = $comment->getBlogger();

        // Check if the user is the author
       // if ($user->getId() != $author->getId()) {
       //     return $this->redirectToRoute("error_403");
      //  }
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard');
    }
}
