<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Film;
use App\Form\CommentaireType;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/', name: 'film')]
    public function index(FilmRepository $repo): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $repo->findAll()
        ]);
    }







    /**
     * @Route("/unfilm/{id}", name="unfilm")
     */
    public function show(Film $film)
    {

        return $this->render('film/show.html.twig',
            [ 'film'=>$film ]);
    }







    /**
     * @Route("/film/new", name="new_film",priority="2")
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {

        $film = new Film();

        $formulaire = $this->createForm(FilmType::class, $film);

        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {

            $film->setReleasedAt(new \DateTime);
            $film->setUser($this->getUser());

            $manager->persist($film);
            $manager->flush();

            $this->addFlash('vert', 'film bien enregistré');

            return $this->redirectToRoute('film', ['id'=>$film->getId()]);
        }

        return $this->renderForm('film/new.html.twig', [
            'formulaire'=>$formulaire
        ]);

    }








    /**
     * @Route ("/film/change/{id}", name="change", priority = "2")
     * @return Response
     */
    public function change(Film $film, Request $request, EntityManagerInterface $manager)
    {

        $formulaire = $this->createForm(FilmType::class, $film);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid() )
        {

            $film = $formulaire->getData();

            $manager->persist($film);
            $manager->flush();

            return $this->redirectToRoute('film');
        }


        return $this->renderForm("film/change.html.twig", ["formulaire"=>$formulaire]);
    }








    /**
    * @Route ("/film/supprimer/{id}", name="film_suppression", priority = "1")
    */
    public function supprimer(Film $film = null, EntityManagerInterface $manager)
    {

        if($film)
        {
        $manager->remove($film);
        $manager->flush();
        }

        return $this->redirectToRoute('film');

    }



















}
