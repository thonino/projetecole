<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    // #[Security("is_granted('ROLE_ADMIN')")]
    public function index(): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('home/index.html.twig');
    }
}
