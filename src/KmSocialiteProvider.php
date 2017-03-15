<?php

namespace Voh\KmSocialite;

use Illuminate\Http\Request;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class KmSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique KmSocialiteProvider Identifier.
     */
    const IDENTIFIER = 'KM';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['profile', 'email', 'permission'];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * @var bool
     */
    protected $stateless = true;

    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl);
        $this->baseUrl = config('services.km.url');
    }


    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase("{$this->baseUrl}/oauth/authorize", $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return "{$this->baseUrl}/oauth/token";
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post("{$this->baseUrl}/api/user/me", [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'job' => $user['job'],
            'unit' => $user['unit'],
            'class_no' => $user['classNo'],
            'seat' => $user['seat'],
            'permission' => $user['permission'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
