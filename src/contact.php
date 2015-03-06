<?php

  class Contact {

    private $name;
    private $phone;
    private $address;


    //construct takes name, phone, and address params and initializes object
    function __construct($n, $p, $a)
    {
      $this->name = $n;
      $this->phone = $p;
      $this->address = $a;
    }

    //getters
    function getName() {
      return $this->name;
    }

    function getPhone() {
      return $this->phone;
    }

    function getAddress() {
      return $this->address;
    }

    //setters
    function setName($new_name) {
      $this->name = $new_name;
    }

    function setPhone($new_phone) {
      $this->phone = $new_phone;
    }

    function setAddress($new_address) {
      $this->address = $new_address;
    }

    //save the contact object into the $_SESSION array
    function save() {
      array_push($_SESSION['contacts'], $this);
    }

    static function getContacts() {
      return $_SESSION['contacts'];
    }

    static function deleteContacts() {
      $_SESSION['contacts'] = array();
    }

  }



 ?>
