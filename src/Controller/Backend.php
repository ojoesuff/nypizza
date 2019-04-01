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
                    $this->session->set('loggedIn', true);
                    $accountType = $repo->getAccountType();
                    return new Response($accountType);
                } else {
                    return new Response("error");
                }

            } else {
                return new Response("error");
            }
        }

        //add menu item to session
        if($type === "addItemToSession") {
            $name = $request->request->get("name");
            $size = $request->request->get("size");
            $qty = $request->request->get("qty");

            $item = array('name'=>$name, 'size'=>$size, 'qty'=>$qty);

            if(!$this->session->get("cart")) {
                $this->session->set("cart", array());
            }

            $cart = $this->session->get("cart");

            array_push($cart, $item);

            $this->session->set("cart", $cart);

            return new Response(var_dump($cart));
        }

        //add custom pizza to session
        if($type === "addCustomPizzaToSession") {
            $name = $request->request->get("name");
            $size = $request->request->get("size");
            $qty = $request->request->get("qty");
            $ham = $request->request->get("ham");
            $chicken = $request->request->get("chicken");
            $pepperoni = $request->request->get("pepperoni");
            $sweetcorn = $request->request->get("sweetcorn");
            $tomato = $request->request->get("tomato");
            $peppers = $request->request->get("peppers");


            $item = array('name'=>$name, 'size'=>$size, 'qty'=>$qty, 'ham'=>$ham, 
            'chicken'=>$chicken,'pepperoni'=>$pepperoni, 'sweetcorn'=>$sweetcorn, 
            'tomato'=>$tomato, 'peppers'=>$peppers);

            //create cart as empty array if it doesn't exist
            if(!$this->session->get("cart")) {
                $this->session->set("cart", array());
            }

            $cart = $this->session->get("cart");

            array_push($cart, $item);

            $this->session->set("cart", $cart);

            return new Response(var_dump($cart));
        }

        //clear session on logout
        if($type === "logout") {
            $this->session->clear();

            return new Response("Session cleared!!");
        }

        //update session cart with new quantities
        if($type === "updateCart") {
            $qty = (int)$request->request->get("qty");
            $index = (int)$request->request->get("index");

            $cart = $this->session->get("cart");

            array_splice($cart, $index, 1); 

            $this->session->set("cart", $cart);

            //delete item if qty is 0
            if($qty === 0) {
                // $this->session->remove("cart[$index]");
                
            }

            return new Response("no");
            
        }

        return new Response("default");
    }

    
}