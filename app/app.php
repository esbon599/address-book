<?php

  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../src/contact.php';

  //start cookie session for browser
  session_start();

  //if our session array is emtpy
  if(empty($_SESSION['contacts']))
  {
    $_SESSION['contacts'] = array();
  }


  $app = new Silex\Application();
  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views/'));

  $app->get('/', function() use ($app) {

    return $app['twig']->render('homepage.twig', array('contacts' => Contact::getContacts()));
  });

  $app->post('/create_contact', function() use ($app) {

    //create a variable in case there is an error
    $error = "";

    //check if we have valid post variables and we aren't coming from a delete menu
    if($_POST['name'] && $_POST['phone'] && $_POST['address']) {

      //create a contact object
      $contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
      //save the object into the session
      $contact->save();
    } else {
      //if we don't have valid input, tell the user
      $error = "Please fill out all fields!";
    }

    return $app['twig']->render('create_contact.twig', array('contact' => $contact, 'error' => $error));

  });

  $app->post('/', function() use ($app) {

    return $app['twig']->render('homepage.twig', array('contacts' => Contact::getContacts()));
  });

  $app->post('/delete', function() use ($app) {

    //created the deleted variables
    $deleted_name = "";
    $deleted_phone = "";
    $deleted_address = "";
    $button = $_POST['button']; //check which page we should be on

    //if we are coming from the homepage
    if($button == "homepage") {

      //get the variables from homepage.twig
      $deleted_name = $_POST['name'];
      $deleted_phone = $_POST['phone'];
      $deleted_address = $_POST['address'];

      //save the info to be deleted into the session
      $_SESSION['delete'] = array($deleted_name, $deleted_phone, $deleted_address);

      //if we are coming from the "YES" delete button clicked
    } else {

      //get our info that was saved in the session
      $deleted_name = $_SESSION['delete'][0];
      $deleted_phone = $_SESSION['delete'][1];
      $deleted_address = $_SESSION['delete'][2];

      //loop through out stored contacts and see if any match the one we want to delete
      foreach($_SESSION['contacts'] as $key => $contact) {

        //if so take it out of the session array
        if($_SESSION['delete'][0] == $contact->getName() && $_SESSION['delete'][1] == $contact->getPhone() && $_SESSION['delete'][2] == $contact->getAddress()) {
          unset($_SESSION['contacts'][$key]);
        }
      }
    }

    return $app['twig']->render('delete.twig', array('phone' => $deleted_phone, 'name' => $deleted_name, 'address' => $deleted_address, 'button' => $button));
  });

  $app->post('/delete_contacts', function() use ($app) {

    //clear out the SESSION['contacts']
    Contact::deleteContacts();

    return $app['twig']->render('delete_all.twig');
  });



  return $app;


 ?>
