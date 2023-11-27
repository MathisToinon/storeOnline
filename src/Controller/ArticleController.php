<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends AbstractController
{
    #[Route('/api/articles', name: 'app_article', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticleController.php',
        ]);
    }
    #[Route('/api/articles/list', name: 'articleList', methods: ['GET'])]
    public function getArticlesList(ArticleRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {
        $articleList = $articleRepository->findAll();

        $jsonArticleList = $serializer->serialize($articleList, 'json', ['groups' => 'getArticle']);
        return new JsonResponse($jsonArticleList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/articles/{id}', name: 'detailArticle', methods: ['GET'])]
    public function getArticleById(Article $article, SerializerInterface $serializer): JsonResponse
    {
        $jsonArticle = $serializer->serialize($article, 'json', ['groups' => 'getArticle']);
        return new JsonResponse($jsonArticle, Response::HTTP_OK, [], true);
    }

    #[Route('/api/articles/{id}', name: 'deleteArticle', methods: ['DELETE'])]
    public function deleteBook(Article $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/articles/list', name:"createArticle", methods: ['POST'])]
    public function createArticle(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ): JsonResponse {

        $article = $serializer->deserialize($request->getContent(), Article::class, 'json');

        // On vérifie les erreurs
        $errors = $validator->validate($article);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($article);
        $em->flush();

        $jsonArticle = $serializer->serialize($article, 'json', ['groups' => 'getArticle']);

        $location = $urlGenerator->generate('detailArticle', ['id' => $article->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonArticle, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/articles/{id}', name:"updateArticle", methods:['PUT'])]

    public function updateArticle(Request $request, SerializerInterface $serializer, Article $currentArticle, EntityManagerInterface $em): JsonResponse
    {
        $updatedArticle = $serializer->deserialize($request->getContent(),
            Article::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentArticle]);
        $content = $request->toArray();
        //manque le changement ICI
        $em->persist($updatedArticle);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
