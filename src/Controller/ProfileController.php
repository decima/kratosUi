<?php

namespace App\Controller;

use App\Services\Kratos\Kratos;
use Ory\Kratos\Client\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile",name="profile")
     */
    public function profile(Request $request, Kratos $kratos)
    {
        $identity = $kratos->getCurrentAuthenticatedUser($request);
        return $this->render("profile/index.html.twig", ["identity" => $identity]);
    }

}
