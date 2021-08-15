<?php


namespace App\Security;


use App\Services\Kratos\Kratos;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class KratosAuthenticator extends AbstractAuthenticator
{
    private Kratos $kratos;

    public function __construct(Kratos $kratos)
    {
        $this->kratos = $kratos;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get("_route") == "auth_login_callback";
    }

    public function authenticate(Request $request): PassportInterface
    {
        try {
            $profile = @$this->kratos->getCurrentAuthenticatedUser($request);
            return new SelfValidatingPassport(new UserBadge($profile->getIdentity()["id"], function () use ($profile) {
                $user = new User();
                $user->id = $profile->getIdentity()["id"];
                $user->email = $profile->getIdentity()["traits"]["email"];
                $user->company = $profile->getIdentity()["traits"]["company"];
                return $user;
            }));
        } catch (\Exception $exception) {
            throw new CustomUserMessageAuthenticationException('An error occured');
        }

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse("/auth/login");

        // TODO: Implement onAuthenticationFailure() method.
    }
}