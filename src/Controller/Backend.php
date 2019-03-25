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

        $type = $request->request->get("type");

        if($type === "register") {
            
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

            return new Response("success");
        }
        
        if($type === "login") {

            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $repo = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                'email' => $email
            ]);           
            //check if hashed password matches DB
            if($repo) {
                $passwordCorrect = password_verify($password, $repo->getPassword());

                if($passwordCorrect) {
                    $accountType = $repo->getAccountType();
                    return new Response($accountType);
                } else {
                    return new Response("error");
                }

            } else {
                return new Response("error");
            }
        }
        return new Response("default");
    }

    
}