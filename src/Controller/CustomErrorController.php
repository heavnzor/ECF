<?php
namespace App\Controller;

use App\Entity\User;
use App\Controller\imgAndSlogan;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomErrorController extends AbstractController {

    
public function show(imgAndSlogan $imgAndSlogan, FlattenException $exception){
        $this->getUser() ? $user = $this->getUser() : $user = new User();
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'img' => $imgAndSlogan->getImg(),
            'slogan' => $imgAndSlogan->getSlogan(),
            'user' => $user,
            "code" => $exception->getStatusCode(),
            "message" => $exception->getStatusText()
        ]);
}
}