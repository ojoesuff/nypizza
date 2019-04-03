<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\FinalOrder;
use App\Entity\Product;
use App\Entity\CustomPizza;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\JsonResponse;

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
                    $this->session->set('name', $repo->getFirstName());
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
            $total = $request->request->get("total") * $qty;

            $item = array('name'=>$name, 'size'=>$size, 'qty'=>$qty, 'total'=>$total);

            //create cart if it doesn't exist
            if(!$this->session->get("cart")) {
                $this->session->set("cart", array());
            }
            //create cart total if doesn't exist
            if(!$this->session->get("cartTotal")) {
                $this->session->set("cartTotal", 0);
            }

            //push item into cart array
            $cart = $this->session->get("cart");
            array_push($cart, $item);
            $this->session->set("cart", $cart);
            //increment cart total by item total
            $cartTotal = $this->session->get("cartTotal");
            $cartTotal += $total;
            $this->session->set("cartTotal", $cartTotal);        


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
            $total = $request->request->get("total") * $qty;


            $item = array('name'=>$name, 'size'=>$size, 'qty'=>$qty, 'ham'=>$ham, 
            'chicken'=>$chicken,'pepperoni'=>$pepperoni, 'sweetcorn'=>$sweetcorn, 
            'tomato'=>$tomato, 'peppers'=>$peppers, 'total' => $total);

            //create cart as empty array if it doesn't exist
            if(!$this->session->get("cart")) {
                $this->session->set("cart", array());
            }
            //create cart total if doesn't exist
            if(!$this->session->get("cartTotal")) {
                $this->session->set("cartTotal", 0);
            }

            //push item into cart array
            $cart = $this->session->get("cart");
            array_push($cart, $item);
            $this->session->set("cart", $cart);
            //increment cart total by item total
            $cartTotal = $this->session->get("cartTotal");
            $cartTotal += $total;
            $this->session->set("cartTotal", $cartTotal); 

            return new Response(var_dump($cart));
        }

        //clear session on logout
        if($type === "logout") {
            $this->session->clear();

            return new Response("Session cleared!!");
        }

        //update session cart with new quantities
        if($type === "updateCart") {
            $newQty = (int)$request->request->get("qty");
            $index = (int)$request->request->get("index");

            $cart = $this->session->get("cart");

            $cartTotal = $this->session->get("cartTotal");
            $total =  $cart[$index]["total"];

             //delete item if qty is 0
            if($newQty === 0) {
                //deletes item from array and reindexes
                array_splice($cart, $index, 1); 
                $cartTotal -= $total;               
            } else {
                //get cart item price and update item total
                $oldQty = $cart[$index]["qty"]; 
                $price =  $total / $oldQty;                 
                $qtyDifference = $newQty - $cart[$index]["qty"];

                if($qtyDifference > 0) {
                    //if new qty is greater than old, add more to cart total
                    $cartTotal += $price * $qtyDifference;
                } elseif ($qtyDifference < 0) {
                    //if new qty is lass than old, remove value from cart total
                    $cartTotal -= $price * abs($qtyDifference);
                }      
                
                $cart[$index]["total"] = $price * $newQty;
                //update new quantity in cart
                $cart[$index]["qty"] = $newQty;
                
            }
            
            $this->session->set("cartTotal", $cartTotal);
            //push updated cart to session
            $this->session->set("cart", $cart);

            return new Response("Cart updated");            
        }

        if($type === "submitOrder") {
            $customer = new User();
            $order = new FinalOrder();

            $entityManager = $this->getDoctrine()->getManager();

            $customerId = $this->session->get("id");            
            //add reference to customer
            $customerRef = $entityManager->getReference('App\Entity\User', $customerId);            

            //add address to DB
            $addressLine1 = $request->request->get("addressLine1");
            $addressLine2 = $request->request->get("addressLine2");
            $addressLine3 = $request->request->get("addressLine3");
            $county = $request->request->get("county");
            $eircode = $request->request->get("eircode");  
            $total = $this->session->get("cartTotal");          

            $order->setAddressLine1($addressLine1);
            $order->setAddressLine2($addressLine2);
            $order->setAddressLine3($addressLine3);
            $order->setCounty($county);
            $order->setEircode($eircode);
            $order->setOrderStatus("Pending");
            $order->setDateCreated(date_create());
            $order->setCustomerId($customerRef);
            $order->setTotal($total);

            $entityManager->persist($order);
            $entityManager->flush();

            //get all orders
            $allOrders = $this->getDoctrine()->getRepository(FinalOrder::class)->findBy([
                'customerId' => $customerId
            ]);
            //get most recent order in array
            $lastOrder = $allOrders[sizeOf($allOrders) - 1];

            $cart = $this->session->get("cart");

            //loop through each cart item and add to DB
            foreach ($cart as $item) {
                $entityManager = $this->getDoctrine()->getManager();
                //set toppings and add custom pizza to DB
                if($item["name"] === "custom-pizza") {
                    
                    $customPizza = new CustomPizza();
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
                    $customPizza->setTotal($item["total"]);
                    $customPizza->setQuantity($item["qty"]);
                    $customPizza->setSize($item["size"]);
                    $customPizza->setOrderId($lastOrder);

                    $entityManager->persist($customPizza); 
                    $entityManager->flush();                    
                } else {
                    $product = new Product();
                    $entityManager = $this->getDoctrine()->getManager();                    

                    $name = $item["name"];
                    $size = $item["size"];
                    if($item["size"] === null) {
                        $size = "";
                    }
                    $qty = (int)$item["qty"];

                    $product->setName($name);
                    $product->setSize($size);
                    $product->setQuantity($qty);
                    $product->setPrice($item["total"]);
                    $product->setOrderId($lastOrder);

                    $entityManager->persist($product); 
                    $entityManager->flush(); 

                }                
            }            
            //remove cart from session
            $this->session->remove("cart");    
            $this->session->remove("cartTotal");    
          
        }

        //get daily report by selected date
        if($type === "dailyUpdate") {
            $date = $request->request->get("date");            
            $formattedDate = date_create($date);
            $totalOrders = $this->getDoctrine()->getRepository(FinalOrder::class)->totalDailyOrders($formattedDate);
            $totalRevenue = $this->getDoctrine()->getRepository(FinalOrder::class)->sumOfDailyOrderRevenue($formattedDate);
            
            //ensures 0 is returned for both parameters if no data is found
            if($totalOrders == 0) {
                $totalRevenue = 0;
            }

            return new Response("$totalOrders $totalRevenue");
        }
        //get weekly report by selected week
        if($type === "weeklyUpdate") {
            $date = $request->request->get("date"); 
            $formattedDate = date_create($date);            
            $totalOrders = $this->getDoctrine()->getRepository(FinalOrder::class)->totalWeeklyOrders($formattedDate);
            $totalRevenue = $this->getDoctrine()->getRepository(FinalOrder::class)->sumOfWeeklyOrderRevenue($formattedDate);
            
            //ensures 0 is returned for both parameters if no data is found
            if($totalOrders == 0) {
                $totalRevenue = 0;
            }

            return new Response("$totalOrders $totalRevenue");
        }

        //get monthly report by selected month
        if($type === "monthlyUpdate") {
            $date = $request->request->get("date"); 
            $formattedDate = date_create($date); 
            $totalOrders = $this->getDoctrine()->getRepository(FinalOrder::class)->totalMonthlyOrders($formattedDate);
            $totalRevenue = $this->getDoctrine()->getRepository(FinalOrder::class)->sumOfMonthlyOrderRevenue($formattedDate);
            
            //ensures 0 is returned for both parameters if no data is found
            if($totalOrders == 0) {
                $totalRevenue = 0;
            }

            return new Response("$totalOrders $totalRevenue");
        }

        if($type === "getPendingOrders") {

            $pendingOrders = $this->getDoctrine()->getRepository(FinalOrder::class)->findBy([
                'orderStatus' => "Pending"
            ]);

            $pendingOrdersArray = array();            

            foreach ($pendingOrders as $order) { 
                
                $orderArray = array();

                $orderId = $order->getId();
                $addressLine1 = $order->getAddressLine1();
                $addressLine2 = $order->getAddressLine2();
                $addressLine3 = $order->getAddressLine3();
                $county = $order->getCounty();
                $eircode = $order->getEircode();

                $orderDetails = array("id" => $orderId, "addressLine1" => $addressLine1, 
                "addressLine2" => $addressLine2, "addressLine3" => $addressLine3,
                "county" => $county, "eircode" => $eircode);

                array_push($orderArray, ["details" => $orderDetails]);

                //get all items associated with order
                $orderItems = $order->getProductId();
                foreach($orderItems as $item) {
                    //get parameters to add to front end
                    $qty = $item->getQuantity();
                    $size = $item->getSize();
                    $name = $item->getName();

                    //add parameters to array
                    $itemArray = array("name" => $name, "qty" => $qty, "size" => $size);
                    //add to items
                    array_push($orderArray, ["order_item" => $itemArray]);
                }


                //get all custom pizzas associated with order
                $customPizzas = $order->getCustomPizzas();                 
                foreach($customPizzas as $customPizza) {
                    //get parameters to add to front end
                    $size = $customPizza->getSize();
                    $qty = $customPizza->getQuantity();
                    $ham = $customPizza->getHam();
                    $chicken = $customPizza->getChicken();
                    $pepperoni = $customPizza->getPepperoni();
                    $sweetcorn = $customPizza->getSweetcorn();
                    $tomato = $customPizza->getTomato();
                    $peppers = $customPizza->getPeppers();

                    $customPizzaArray = array("name" => "custom-pizza", "qty" => $qty, "size" => $size, "ham" => $ham,
                    "chicken" => $chicken, "pepperoni" => $pepperoni, "sweetcorn" => $sweetcorn,
                    "tomato" => $tomato, "peppers" => $peppers );

                    array_push($orderArray, ["custom_pizza" => $customPizzaArray]);
                }

                array_push($pendingOrdersArray, $orderArray);
         
            }            
            
            return new JsonResponse($pendingOrdersArray);

        }

        if($type === "deleteOrder") {
            $orderId = $request->request->get("orderId"); 
            
            $entityManager = $this->getDoctrine()->getManager(); 
            //get order and each product associated with order
            $products = $entityManager->getRepository(Product::class)->findBy([
                'orderId' => $orderId
            ]); 
            $customPizzas = $entityManager->getRepository(CustomPizza::class)->findBy([
                'orderId' => $orderId
            ]);
            $order = $entityManager->getRepository(FinalOrder::class)->findOneBy([
                'id' => $orderId
            ]);

            if ($order) {
                foreach($products as $product) {
                    $entityManager->remove($product);
                }
                foreach($customPizzas as $customPizza) {
                    $entityManager->remove($customPizza);
                }                
                
                $entityManager->remove($order);
                $entityManager->flush();
                
                return new Response($orderId." deleted");

            } else {
                throw $this->createNotFoundException(
                    'No order found for order id '.$orderId
                );
            }
        }
        return new Response("default");
    }

    
}