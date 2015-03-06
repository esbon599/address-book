<?php

  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../src/contact.php';

  //start cookie session for browser
  session_start();


  $app = new Silex\Application();
  $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views/'));

  $app->get('/', function() use ($app) {
    return $app['twig']->render('homepage.twig');
  });

  $app->post('/', function() use ($app) {
    

    return $app['twig']->render('homepage.twig');
  });



  return $app;


 ?>
