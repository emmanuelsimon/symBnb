<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError'=>$error!==null,
            'userName'=>$username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {

    }

    /**
     * Permet de créer un utilisateur
     *
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form=$this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash=$encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * Permet la modification du profil de l'utilisateur
     *
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profil(Request $request, ObjectManager $manager)
    {
        $user=$this->getUser();
        $form=$this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
        }

        $this->addFlash(
            'success',
            'Votre profil a bien été modifié.'
        );


        return $this->render('account/profile.html.twig', [
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
}
