<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 

use Symfony\Component\HttpFoundation\Session\SessionInterface;  

class Backend extends AbstractController {

    //initialise session
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
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
                    //set id in session
                    $this->session->set('id', $repo->getId());
                    $accountType = $repo->getAccountType();
                    return new Response($accountType);
                } else {
                    return new Response("error");
                }

            } else {
                return new Response("error");
            }
        }

        if($type === "addItemToSession") {
            $name = $request->request->get("name");
            $size = $request->request->get("size");
            $qty = $request->request->get("qty");

            $item = array($name, $size, $qty);

            if(!$this->session->get("cart")) {
                $this->session->set("cart", array());
            }

            $cart = $this->session->get("cart");

            array_push($cart, $item);

            $this->session->set("cart", $cart);

            return new Response(var_dump($cart));
        }

        if($type === "logout") {
            $this->session->clear();

            return new Response("Session cleared!!");
        }
        return new Response("default");
    }

    
}