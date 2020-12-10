<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\Trainer;
use App\Entity\Type;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use App\Repository\TrainerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/pokemon")
 */
class PokemonController extends AbstractController
{
    /**
     * @Route("/", name="pokemon_index", methods={"GET"})
     */
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->json($pokemonRepository->findAll(),200,[],['groups'=>'post:read']);
    }

    /**
     * @Route("/", name="pokemon_new", methods={"POST"})
     */
    public function new(Request $request,SerializerInterface $serializer, ValidatorInterface $validator, PokemonRepository $pokemonRepository): Response
    {
        try {
            $TtoP = $serializer->deserialize($request->getContent(),Pokemon::class,'json');
            $entityManager = $this->getDoctrine()->getManager();
            $json = json_decode($request->getContent(), true);
            $pokemon = $pokemonRepository->findBy(['nom'=>$json['nom']]);
            if (count($pokemon)>0) 
            {
                return $this->json([
                    'status' =>  400,
                    'message' => 'Ce pokémon ('.$json['nom'].') existe déjà '
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
     * @Route("/{id}", name="pokemon_edit", methods={"PUT"})
     */
    public function pokemonEdit(Request $request,Pokemon $pokemon, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        // $TtoP = $serializer->deserialize($request->getContent(),Pokemon::class,'json');
        
        try {
            $json = json_decode($request->getContent(), true);
            
            $entityManager = $this->getDoctrine()->getManager();
            
            $errors=$validator->validate($pokemon);
            if (count($errors)>0) {
                return $this->json($errors,400);
            }
            // dd($json['idtrainer'],$errors);
            // $pokemon->setNom($request->get('nom'));
            
            if ( (array_key_exists ("levelevolution", $json) ) && ($json['levelevolution']) ) 
            {
                $pokemon->setLevelevolution($json['levelevolution']);
            }
            if ( (array_key_exists ("idtrainer", $json) ) && ($json['idtrainer']) )
            {
                    $trainer = $this->getDoctrine()->getRepository(Trainer::class)->find($json['idtrainer']);
                    if ($trainer) $pokemon->setIdtrainer($trainer);
            }
            if ( (array_key_exists ("idtype1", $json) ) && ($json['idtype1']) )
            {
                $type1 = $this->getDoctrine()->getRepository(Type::class)->find($json['idtype1']); 
                if ($type1) $pokemon->addIdtype($type1);
            }
            if ( (array_key_exists ("idtype2", $json) ) && ($json['idtype2']) )
            {
                $type2 = $this->getDoctrine()->getRepository(Type::class)->find($json['idtype2']); 
                if ($type2) $pokemon->addIdtype($type2);
            }
            $entityManager->persist($pokemon);
            $entityManager->flush();
            return $this->json($pokemon,201,[],['groups'=>'post:read']);
        }
        catch (NotEncodableValueException $e) {
            return $this->json ([
                'status' =>  400,
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * @Route("/new", name="pokemon_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_index');
        }

        return $this->render('pokemon/new.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="pokemon_show", methods={"GET"})
    //  */
    // public function show(Pokemon $pokemon): Response
    // {
    //     return $this->render('pokemon/show.html.twig', [
    //         'pokemon' => $pokemon,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="pokemon_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Pokemon $pokemon): Response
    // {
    //     $form = $this->createForm(PokemonType::class, $pokemon);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('pokemon_index');
    //     }

    //     return $this->render('pokemon/edit.html.twig', [
    //         'pokemon' => $pokemon,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="pokemon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pokemon $pokemon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokemon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pokemon_index');
    }
}
