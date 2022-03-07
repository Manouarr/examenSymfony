<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire/suppr/{id}', name: 'suppressioncommentaire')]
    public function supprimer(Commentaire $commentaire = null, EntityManagerInterface $manager): Response
    {

        if ($commentaire)
        {

            $id = $commentaire->getFilm()->getId();
            $manager->remove($commentaire);
            $manager->flush();
            return $this->redirectToRoute('film', ['id'=>$id]);
        }

        return $this->redirectToRoute('film');

    }







    /**
     * @Route ("commentaire/change/{id}", name="changercommentaire")
     */
    public function change(Commentaire $commentaire, Request $request, EntityManagerInterface $manager)
    {

        $formulaire = $this->createForm(CommentaireType::class, $commentaire);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid() )
        {

            $commentaire = $formulaire->getData();

            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('unfilm', ['id'=>$commentaire->getFilm()->getId()
            ]);
        }

        return $this->renderForm("commentaire/change.html.twig", [
            'formulaire' =>$formulaire
        ]);
    }
}
