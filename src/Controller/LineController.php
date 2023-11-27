<?php

namespace App\Controller;

use App\Entity\Line;
use App\Repository\ArticleRepository;
use App\Repository\LineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LineController extends AbstractController
{
    #[Route('/api/line', name: 'line')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LineController.php',
        ]);
    }

    #[Route('/api/lines/list', name: 'lineList',methods: ['GET'])]
    public function getLineList(LineRepository $lineRepository, SerializerInterface $serializer): JsonResponse
    {
        $lineList = $lineRepository->findAll();
        $jsonLineList = $serializer->serialize($lineList, 'json', ['groups' => 'getLine']);
        return new JsonResponse($jsonLineList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/lines/{id}', name: 'detailLine', methods: ['GET'])]
    public function getLineById(Line $line, SerializerInterface $serializer): JsonResponse
    {
        $jsonLine = $serializer->serialize($line, 'json', ['groups' => 'getLine']);
        return new JsonResponse($jsonLine, Response::HTTP_OK, [], true);
    }

    #[Route('/api/lines/{id}', name: 'deleteLine', methods: ['DELETE'])]
    public function deleteBook(Line $line, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($line);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/lines/list', name:"createLine", methods: ['POST'])]
    public function createLine(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ArticleRepository $articleRepository): JsonResponse
    {

        $line = $serializer->deserialize($request->getContent(), Line::class, 'json');


        $content = $request->toArray();
        $idArticle = $content['idArticle'];

        $line->setArticles($articleRepository->find($idArticle));
        $em->persist($line);
        $em->flush();

        $jsonLine = $serializer->serialize($line, 'json', ['groups' => 'getLine']);

        $location = $urlGenerator->generate('detailLine', ['id' => $line->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonLine, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
