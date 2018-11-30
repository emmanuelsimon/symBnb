<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker=Factory::create('FR-fr');
        $users=[];
        $genres=['male', 'female'];
        // nous gérons les user
        for($i =1; $i<=10; $i++) {
            $user=new User();

            // gestion des images en fonction du sexe
            $genre=$faker->randomElement($genres);
            $photo = 'https://randomuser.me/api/portraits/';
            $sexe='men';

            if ($genre=='female') {
                $sexe='women';
            }
            $photo.=$sexe.'/'.mt_rand(1, 99).'.jpg';

            $hash=$this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPicture($photo)
                ->setIntroduction($faker->sentence)
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setHash($hash);
            $manager->persist($user);
            $users[]=$user;
        }

        // nous gérons les annonces
        for($i=1; $i<30; $i++) {
            $title=$faker->sentence(6);
            $coverImage=$faker->imageUrl(1000,350);
            $intro=$faker->paragraph(2);
            $content='<p>'.join('</p><p>', $faker->paragraphs(5)).'</p>';
            $user=$users[mt_rand(0, count($users)-1)];
            $ad = new Ad();
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($intro)
                ->setContent($content)
                ->setPrice(mt_rand(50, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
            for($j=1; $j<mt_rand(2,5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}
