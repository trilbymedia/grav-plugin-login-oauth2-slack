<?php
namespace Grav\Plugin\Login\OAuth2\Providers;

use Grav\Common\Grav;

class SlackProvider extends BaseProvider
{
    protected $name = 'Slack';
    protected $classname = 'AdamPaterson\\OAuth2\\Client\\Provider\\Slack';
    protected $config;

    /** @var Slack */
    protected $provider;

    public function __construct(array $options)
    {
        $this->config = Grav::instance()['config'];

        $options += [
            'clientId'      => $this->config->get('plugins.login-oauth2-slack.client_id'),
            'clientSecret'  => $this->config->get('plugins.login-oauth2-slack.client_secret'),
            'redirectUri'   => $this->config->get('plugins.login-oauth2.callback_uri'),
        ];

        parent::__construct($options);
    }

    public function getAuthorizationUrl()
    {
        $options = ['state' => $this->state];
        $options['scope'] = $this->config->get('plugins.login-oauth2-slack.options.scope');

        return $this->provider->getAuthorizationUrl($options);
    }

    public function getUserData($user)
    {
        $data = $user->toArray();
xdebug_break();
        $data_user = [
            'id'         => $user->getId(),
            'login'      => $user->getName(),
            'fullname'   => $data['user']['profile']['real_name'],
            'email'      => $user->getEmail(),
            'slack'      => [
                'location'   => $data['user']['tz'],
                'avatar_url' => $data['user']['profile']['image_512'],
            ]
        ];

        return $data_user;
    }
}