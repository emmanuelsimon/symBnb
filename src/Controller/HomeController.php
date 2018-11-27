<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/11/2018
 * Time: 12:22
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{

    /**
     * @Route("/hello/{prenom}", name="hello")
     */
    public function hello($prenom = 'inconnu') {
        return $this->render(
            'hello.html.twig',
            [
                'prenom'=> $prenom
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home() {
        $prenom = ['lior'=>31, 'joseph'=>12, 'anne'=>55];
        return $this->render(
            'home.html.twig',
            [
                'title'=>'Bonjour à tous.......',
                'age'=>17,
                'tableau'=>$prenom
            ]
        );
    }
}