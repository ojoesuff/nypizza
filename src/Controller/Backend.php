<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\CustomPizza;
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

             //delete item if qty is 0
            if($qty === 0) {
                //deletes item from array and reindexes
                array_splice($cart, $index, 1);                 
            } else {
                //update new quantity in cart
                $cart[$index]["qty"] = $qty;
            }

            //push updated cart to session
            $this->session->set("cart", $cart);

            return new Response("Cart updated");            
        }

        if($type === "submitOrder") {
            $customer = new User();
            $order = new Order();            
            $id = $this->session->get("id");


            $entityManager = $this->getDoctrine()->getManager();

            // $customer = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            //     'id' => $id
            // ]);

            $cart = $this->session->get("cart");

            foreach ($cart as $item) {
                $customPizza = new CustomPizza();
                $entityManager = $this->getDoctrine()->getManager();
                //set toppings
                if($item["name"] === "custom-pizza") {
                    if($item["ham"] === "true") {
                        $customPizza->setHam(true);
                    } else {
                        $customPizza->setHam(false);
                    }
                    if($item["chicken"] === "true") {
                        $customPizza->setChicken(true);
                    } else {
                        $customPizza->setChicken(false);
                    }
                    if($item["pepperoni"] === "true") {
                        $customPizza->setPepperoni(true);
                    } else {
                        $customPizza->setPepperoni(false);
                    }
                    if($item["sweetcorn"] === "true") {
                        $customPizza->setSweetcorn(true);
                    } else {
                        $customPizza->setSweetcorn(false);
                    }
                    if($item["tomato"] === "true") {
                        $customPizza->setTomato(true);
                    } else {
                        $customPizza->setTomato(false);
                    }
                    if($item["peppers"] === "true") {
                        $customPizza->setPeppers(true);
                    } else {
                        $customPizza->setPeppers(false);
                    }
                    $customPizza->setTotal(20);
                    $customPizza->setQuantity($item["qty"]);

                    $entityManager->persist($customPizza); 
                    $entityManager->flush();                    
                } 
            }

            

            // $addressLine1 = $request->request->get("addressLine1");
            // $addressLine2 = $request->request->get("addressLine2");
            // $addressLine3 = $request->request->get("addressLine3");
            // $county = $request->request->get("county");
            // $eircode = $request->request->get("eircode");
            // $customerId = $this->session->get("id");            

            // $order->setAddressLine1($addressLine1);
            // $order->setAddressLine2($addressLine2);
            // $order->setAddressLine3($addressLine3);
            // $order->setCounty($county);
            // $order->setStatus("Pending");
            // $order->setDateCreated(date_create());
            // $order->setCustomerId($customer);

            // $entityManager->persist($order);
            // $entityManager->flush();  
            
        }

        return new Response("default");
    }

    
}