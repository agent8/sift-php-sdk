<?php
namespace Easilydo\EmailConnections;

/**
 * Class YahooConnection
 *
 * @package Easilydo
 */
class YahooConnection implements \Easilydo\EmailConnections\Connection
{
    /**
     * @var string The Yahoo GUID associated with the user’s Yahoo account
     */
    private $account;

    /**
     * @var string The refresh token for the OAuth2 connection
     */
    private $refreshToken;

    /**
     * @var string The redirect URI that was used for the OAuth2 connection
     */
    private $redirectUri;

    /**
     * Instantiates a new YahooConnection object
     *
     * @param string $account
     * @param string $refreshToken
     * @param string $redirectUri
     */
    public function __construct($account, $refreshToken, $redirectUri)
    {
        $this->account = $account;
        $this->refreshToken = $refreshToken;
        $this->redirectUri = $redirectUri;
    }

    public function getAddBody()
    {
        return [
            'account_type' => 'yahoo',
            'account' => $this->account,
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $this->redirectUri
        ];
    }
}
?>
