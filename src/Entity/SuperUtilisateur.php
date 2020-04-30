<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuperUtilisateurRepository")
 */
class SuperUtilisateur extends Utilisateur
{
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_SUPER_ADMIN']);
        $this->questions = new ArrayCollection();
    }
}
