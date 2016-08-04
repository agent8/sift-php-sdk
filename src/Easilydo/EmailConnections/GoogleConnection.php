<?php
namespace Easilydo\EmailConnections;

/**
 * Class GoogleConnection
 *
 * @package Easilydo
 */
class GoogleConnection implements \Easilydo\EmailConnections\Connection
{
    /**
     * @var string The email address associated with the Google account
     */
    private $emailAddress;

    /**
     * @var string The refresh token for the OAuth2 connection
     */
    private $refreshToken;

    /**
     * Instantiates a new GoogleConnection object
     *
     * @param string $emailAddress
     * @param string $refreshToken
     */
    public function __construct($emailAddress, $refreshToken)
    {
        $this->emailAddress = $emailAddress;
        $this->refreshToken = $refreshToken;
    }

    public function getAddBody()
    {
        return [
            'account_type' => 'google',
            'account' => $this->emailAddress,
            'refresh_token' => $this->refreshToken
        ];
    }
}
?>
