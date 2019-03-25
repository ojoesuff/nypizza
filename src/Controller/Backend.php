<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 

class Backend extends AbstractController {
    /**
     * @Route("/backend", name="backend") methods={"GET", "POST"}
     */
    public function index() {

        $request = Request::createFromGlobals();

        $firstName = $request->request->get("firstName", "none");
        $lastName = $request->request->get("lastName", "none");
        $email = $request->request->get("email", "none");
        $password = $request->request->get("password", "none");
        $accountType = $request->request->get("accountType", "none");

        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setAccountType($accountType);

        $entityManager->persist($user);
        $entityManager->flush();         

        return new Response("Success");
    }
}