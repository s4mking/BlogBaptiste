<?php

namespace App\Controller;

use App\Repository\BlogPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }
}
