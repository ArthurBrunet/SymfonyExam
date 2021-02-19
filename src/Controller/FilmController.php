<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FilmController extends AbstractController
{

    /**
     * @Route("/", name="Accueil")
     */
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'FilmController',
        ]);
    }

    private function serializeJson($objet){
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        return $serializer->serialize($objet, 'json');
    }

    /**
     * @Route("/films", name="films", methods={"GET"})
     * @param FilmRepository $filmRepository
     * @param Request $request
     * @return JsonResponse
     *
     * Gére le get avec un ou plusieur champs (tout les champs possible dans l'entité) et le getall si rien n'est renseigné
     */
    public function films(FilmRepository $filmRepository,Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Film::class)->getFieldNames();
        foreach($metadata as $value){
            if ($request->query->get($value)){
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($filmRepository->findBy($filter)));
    }

    /**
     * @Route("/api/film/create", name="filmCreate", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function filmCreate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $film = new Film();
        $data = json_decode($request->getContent(),true);
        $film
            ->setNom($data['nom'])
            ->setSynopsis($data['synopsis'])
            ->setType($data['type']);
        $em->persist($film);
        $em->flush();
        return JsonResponse::fromJsonString($this->serializeJson($film));
    }
}
