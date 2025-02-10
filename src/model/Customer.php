<?php
namespace Lucpa\Model;
class Customer {
    private $id;
    private $name;

    // Constructeur pour initialiser un client
    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }

    // Getter et Setter pour l'id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter et Setter pour le nom
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
