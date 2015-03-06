<?php

  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../src/contact.php';

  $app = new Silex\Application();

  $app->get('/', function () use ($app) {
    return "HOME";
  });



  return $app;


 ?>
