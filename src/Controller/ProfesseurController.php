<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfesseurController extends AbstractController
{
    /**
     * @Route("/professeur", name="professeur")
     */
    public function index()
    {
        return $this->render('professeur/prof.html.twig');
    }
}
