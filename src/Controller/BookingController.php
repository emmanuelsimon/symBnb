<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, Ad $ad, ObjectManager $manager)
    {
        $booking=new Booking();
        $form=$this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setBooker($this->getUser())
                ->setAd($ad);

            // test date dispo
            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    'les dates choisies ne sont pas disponible'
                );
            } else {
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', ['id'=>$booking->getId(), 'withAlert'=>true]);
            }
        }


        return $this->render('booking/book.html.twig', [
            'ad'=>$ad,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager)
    {
        $comment=new Comment();

        $form=$this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAd($booking->getAd())
                ->setAuthor($this->getUser());

            $manager->persist();
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre annonce commentaire a été enregistré.'
            );

        }

        return $this->render('booking/show.html.twig', [
            'booking'=>$booking,
            'form'=>$form->createView()
        ]);
    }
}
