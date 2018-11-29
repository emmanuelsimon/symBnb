<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AnnonceType;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads=$repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Permet d'ajouter une annonce
     *
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     *
     */
    public function create(Request $request, ObjectManager $manager){
        $ad=new Ad();

        $form=$this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre annonce '.$ad->getTitle().' a bien été enregistrée.'
            );

            return $this->redirectToRoute('ads_show', ['slug'=>$ad->getSlug()]);
        }

        return $this->render('ad/new.html.twig',
            [
                'form'=>$form->createView()
            ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);
    }


}
