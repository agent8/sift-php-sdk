<?php
namespace Easilydo\EmailConnections;

/**
 * Class MicrosoftConnection
 *
 * @package Easilydo
 */
class MicrosoftConnection implements \Easilydo\EmailConnections\Connection
{
    /**
     * @var string The email address associated with the Live account
     */
    private $emailAddress;

    /**
     * @var string The refresh token for the OAuth2 connection
     */
    private $refreshToken;

    /**
     * @var string The redirect URI that was used for the OAuth2 connection
     */
    private $redirectUri;

    /**
     * Instantiates a new MicrosoftConnection object
     *
     * @param string $emailAddress
     * @param string $refreshToken
     * @param string $redirectUri
     */
    public function __construct($emailAddress, $refreshToken, $redirectUri)
    {
        $this->emailAddress = $emailAddress;
        $this->refreshToken = $refreshToken;
        $this->redirectUri = $redirectUri;
    }

    public function getAddBody()
    {
        return [
            'account_type' => 'live',
            'account' => $this->emailAddress,
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $this->redirectUri
        ];
    }
}
?>
