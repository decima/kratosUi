<?php

namespace App\Controller;

use App\Services\Kratos\Kratos;
use Ory\Kratos\Client\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth/login", name="auth_login")
     */
    public function login(Request $request, Kratos $kratos): Response
    {
        if (!$flowId = $request->query->get("flow")) {
            return $this->redirect($kratos->getBrowserUrl() . "/self-service/login/browser");
        }
        try {
            $result = @$kratos->admin()->getSelfServiceLoginFlow($flowId, $request->headers->get("cookie"));
            return $this->render('auth/login.html.twig', [
                'ui' => $result->getUi(),
            ]);
        } catch (ApiException $apiException) {
            return $this->redirectToRoute("auth_login");
        }

    }

    /**
     * @Route("/auth/login/callback", name="auth_login_callback")
     */
    public function loginCallback(Request $request)
    {
        throw new \Exception("This route should not be called directly");

    }


    /**
     * @Route("/auth/registration", name="auth_registration")
     */
    public function registration(Request $request, Kratos $kratos): Response
    {
        if (!$flowId = $request->query->get("flow")) {
            return $this->redirect($kratos->getBrowserUrl() . "/self-service/registration/browser");
        }
        try {
            $result = @$kratos->admin()->getSelfServiceRegistrationFlow($flowId, $request->headers->get("cookie"));
            return $this->render('auth/register.html.twig', [
                'ui' => $result->getUi(),
            ]);
        } catch (ApiException $apiException) {
            return $this->redirectToRoute("auth_registration");
        }
    }

    /**
     * @Route("/auth/logout", name="auth_logout")
     */
    public function logout(Request $request, Kratos $kratos)
    {
        try {
            $result = $kratos->admin()->createSelfServiceLogoutFlowUrlForBrowsers($request->headers->get("cookie"));
        } catch (ApiException $apiException) {
            return $this->redirectToRoute("auth_login");
        }
        return $this->redirect($result->getLogoutUrl());

    }

}
