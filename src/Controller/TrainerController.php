<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Form\TrainerType;
use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/trainer")
 */
class TrainerController extends AbstractController
{
    /**
     * @Route("/", name="trainer_index", methods={"GET"})
     */
    public function index(TrainerRepository $trainerRepository): Response
    {
        return $this->json($trainerRepository->findAll(),200,[],[]);
    }

    /**
     * @Route("/", name="trainer_new", methods={"POST"})
     */
    public function new(Request $request,SerializerInterface $serializer,ValidatorInterface $validator,
                        TrainerRepository $trainerRepository): Response
    {
        try {
            $TtoP = $serializer->deserialize($request->getContent(),Trainer::class,'json');
            $entityManager = $this->getDoctrine()->getManager();
            $json = json_decode($request->getContent(), true);
            $trainer = $trainerRepository->findBy(['name'=>$json['name'],'gender'=>$json['gender']]);
            if (count($trainer)>0) 
            {
                return $this->json([
                    'status' =>  400,
                    'message' => 'Ce nom ('.$json['name'].') existe déjà avec le sexe '.$json['gender']
                ],400);
            }

            $errors=$validator->validate($TtoP);
            if (count($errors)>0) {
                return $this->json($errors,400);
            }
            $entityManager->persist($TtoP);
            $entityManager->flush();
            return $this->json($TtoP,201,[],['groups'=>'post:read']);
        }
        catch (NotEncodableValueException $e) {
            return $this->json ([
                'status' =>  400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/new", name="trainer_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $trainer = new Trainer();
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trainer);
            $entityManager->flush();

            return $this->redirectToRoute('trainer_index');
        }

        return $this->render('trainer/new.html.twig', [
            'trainer' => $trainer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trainer_show", methods={"GET"})
     */
    public function show(Trainer $trainer): Response
    {
        return $this->render('trainer/show.html.twig', [
            'trainer' => $trainer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trainer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trainer $trainer): Response
    {
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trainer_index');
        }

        return $this->render('trainer/edit.html.twig', [
            'trainer' => $trainer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trainer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trainer $trainer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trainer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trainer_index');
    }
}
