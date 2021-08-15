<?php


namespace App\Services\Kratos;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Ory\Kratos\Client\Api\CommonApi;
use Ory\Kratos\Client\Api\MetadataApi;
use Ory\Kratos\Client\Api\PublicApi;
use Ory\Kratos\Client\Api\V0alpha1Api;
use Ory\Kratos\Client\Configuration;
use Ory\Kratos\Client\Model\Meta;
use Ory\Kratos\Client\Model\Session;
use Symfony\Component\HttpFoundation\Request;

class Kratos
{
    private PublicApi $publicApi;
    private MetadataApi $metadataApi;
    private V0alpha1Api $adminApi;
    private CommonApi $commonApi;
    private string $kratosPublicUrl;
    private ClientInterface $client;

    public function __construct(string $kratosPublicUrl, string $kratosAdminUrl)
    {
        $this->client = new Client(['cookies' => true]);
        $publicConf = (new Configuration())->setHost($kratosPublicUrl);
        $adminConf = (new Configuration())->setHost($kratosAdminUrl);
        $this->publicApi = new PublicApi($this->client, $publicConf);
        $this->metadataApi = new MetadataApi($this->client, $adminConf);
        $this->adminApi = new V0alpha1Api($this->client, $adminConf);
        $this->commonApi = new CommonApi($this->client, $adminConf);
        $this->kratosPublicUrl = $kratosPublicUrl;
    }

    public function getBrowserUrl()
    {
        return $this->kratosPublicUrl;
    }

    public function public(): PublicApi
    {
        return $this->publicApi;
    }

    public function metadata(): MetadataApi
    {
        return $this->metadataApi;
    }

    public function admin(): V0alpha1Api
    {
        return $this->adminApi;
    }

    public function common(): CommonApi
    {
        return $this->commonApi;
    }

    public function getCurrentAuthenticatedUser(Request $request):Session
    {
        $response = $this->client->get(
            "/sessions/whoami",
            [
                "base_uri" => $this->getBrowserUrl(),
                "headers" => ["cookie"=>$request->headers->get("cookie")]
            ]
        );
        return new Session(
            json_decode($response->getBody()->getContents(), true)
        );
    }

}