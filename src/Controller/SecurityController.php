<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route(name="api_login", path="api/login_check", methods={"POST"})
     * @return Response
     */
    public function api_login(): Response
    {
        $user = $this->getUser();

        return new Response([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }
}