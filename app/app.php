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

  // $_SESSION['contacts'] = array();


  $app = new Silex\Application();
  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views/'));

  $app->get('/', function() use ($app) {
    var_dump(array_keys(Contact::getContacts()));
    return $app['twig']->render('homepage.twig', array('contacts' => Contact::getContacts()));
  });

  $app->post('/', function() use ($app) {

    //create a variable in case there is an error
    $error = "";

    //check if we are coming from the delete page
    if($_POST['no'] == "no")
    {
      echo "NOT DELETED";
    }
    //check if we have valid post variables
    elseif($_POST['name'] && $_POST['phone'] && $_POST['address']) {
      echo "Valid variables";
      //create a contact object
      $contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
      //save the object into the session
      $contact->save();
    } else {
      $error = "Please fill out all fields!";
      echo "invalid variables";
    }

    var_dump($_SESSION['contacts']);


    return $app['twig']->render('homepage.twig', array('contacts' => Contact::getContacts(), 'error' => $error));
  });

  $app->post('/delete', function() use ($app) {

    var_dump($_POST);

    $deleted_name = "";
    $deleted_phone = "";
    $deleted_address = "";

    //if we are coming from the homepage
    if($_POST['button'] == "homepage") {

      $deleted_name = $_POST['name'];
      $deleted_phone = $_POST['phone'];
      $deleted_address = $_POST['address'];

      //save the info to be deleted into the session
      $_SESSION['delete'] = array($deleted_name, $deleted_phone, $deleted_address);
      echo "added a contact to delete";
      var_dump($_SESSION['delete']);
    } else {

      //loop through out stored contacts and see if any match the one we want to delete
      foreach($_SESSION['contacts'] as $key => $contact) {

        //if so take it out of the session array
        if($_SESSION['delete'][0] == $contact->getName() && $_SESSION['delete'][1] == $contact->getPhone() && $_SESSION['delete'][2] == $contact->getAddress()) {
          unset($_SESSION['contacts'][$key]);
        }
        $count++;
      }
      echo $count;

      var_dump($_SESSION['contacts']);
    }


    return $app['twig']->render('delete.twig', array('phone' => $deleted_phone, 'name' => $deleted_name, 'address' => $deleted_address));
  });

  $app->post('/deleted', function() use ($app) {
    return $app['twig']->render('deleted.twig');
  });



  return $app;


 ?>
