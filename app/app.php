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
    return $app['twig']->render('homepage.twig');
  });

  $app->post('/', function() use ($app) {

    //check if we have valid post variables
    if($_POST['name'] && $_POST['phone'] && $_POST['address']) {
      echo "Valid variables";
      //create a contact object
      $contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
      //save the object into the session
      $contact->save();
    } else {
      echo "invalid variables";
    }

    var_dump($_SESSION['contacts']);


    return $app['twig']->render('homepage.twig');
  });



  return $app;


 ?>
