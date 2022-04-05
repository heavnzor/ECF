<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





$nb = rand(0, 6);
switch ($nb) {
case '0':
$img = 'build/img/bg-masthead.jpg';
$slogan = 'Un meilleur web, un meilleur monde.';
break;
case '1':
$img = 'build/img/img2.png';
$slogan = 'Vers un vrai web3, le web vert.';
break;
case '2':
$img = 'build/img/img3.png';
$slogan = 'Le développement 3.0, le développement écolo.';
break;
case '3':
$img = 'build/img/img4.jpg';
$slogan = "L'avenir de la programmation web.";
break;
case '4':
$img = 'build/img/img5.jpg';
$slogan = 'Participez au monde de demain.';
break;
case '5':
$img = 'build/img/img6.jpg';
$slogan = 'Pour une programmation consciente.';
break;
case '6':
$img = 'build/img/img7.jpg';
$slogan = 'Apprenez à programmer des sites web écologiquement viables';
break;
}
class imgAndSlogan extends AbstractController
{
  

    public function getImg(){
        $n0 = rand(0, 6);
        switch ($n0) {
            case '0':
                $img = 'build/img/bg-masthead.jpg';
                break;
            case '1':
                $img = 'build/img/img2.png';
                break;
            case '2':
                $img = 'build/img/img3.png';
                break;
            case '3':
                $img = 'build/img/img4.jpg';
                break;
            case '4':
                $img = 'build/img/img5.jpg';
                break;
            case '5':
                $img = 'build/img/img6.jpg';
                break;
            case '6':
                $img = 'build/img/img7.jpg';
                break;
        }
        return $img;
    }
        public function getSlogan(){
        $n1 = rand(0, 6);
        switch ($n1) {
            case '0':
                $slogan = 'Un meilleur web, un meilleur monde.';
                break;
            case '1':
                $slogan = 'Vers un vrai web3, le web vert.';
                break;
            case '2':
                $slogan = 'Le développement 3.0, le développement écolo.';
                break;
            case '3':
                $slogan = "L'avenir de la programmation web.";
                break;
            case '4':
                $slogan = 'Participez au monde de demain.';
                break;
            case '5':
                $slogan = 'Pour une programmation consciente.';
                break;
            case '6':
                $slogan = 'Apprenez à programmer des sites web écologiquement viables';
                break;
            }
            return $slogan;
        }

   
    }
