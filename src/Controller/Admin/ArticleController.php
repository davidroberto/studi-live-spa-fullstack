<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="articles_list")
     */
    public function articlesList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('admin/article/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/article/{id}/delete", name="article_delete")
     */
    public function articleDelete($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute("articles_list");
    }

    /**
     * @Route("/admin/article/create", name="article_create")
     */
    public function articleCreate(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article();
        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('/admin/article/article_create.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }

    /**
     * @Route("/admin/article/{id}/update", name="article_update")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('/admin/article/article_create.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);

    }

}
