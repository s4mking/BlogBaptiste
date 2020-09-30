<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog/post")
 */
class BlogPostController extends AbstractController
{
    /**
     * @Route("/", name="blog_post_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="blog_post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blogPost = new BlogPost();
        $blogPost->setAuthor($this->getUser());
        $form = $this->createForm(BlogPostType::class, $blogPost);
        // dd('sam');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('blog_post_index');
        }

        return $this->render('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_show", methods={"GET","POST"})
     */
    public function show(Request $request, BlogPost $blogPost): Response
    {
        $message = new Commentaire();
        $message->setRelatedPost($blogPost);
        $form = $this->createForm(CommentaireFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->persist($blogPost);
            $entityManager->flush();
            return $this->redirectToRoute('blog_post_show', ['id' => $blogPost->getId()]);
        }
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogPost $blogPost): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_post_index');
        }

        return $this->render('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_post_index');
    }
}
