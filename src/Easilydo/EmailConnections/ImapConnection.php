<?php
namespace Easilydo\EmailConnections;

/**
 * Class ImapConnection
 *
 * @package Easilydo
 */
class ImapConnection implements \Easilydo\EmailConnections\Connection
{
    /**
     * @var string The email address for the IMAP account
     */
    private $emailAddress;

    /**
     * @var string The password for the IMAP account
     */
    private $password;

    /**
     * @var string The host for the IMAP account
     */
    private $host;

    /**
     * Instantiates a new ImapConnection object
     *
     * @param string $emailAddress
     * @param string $password
     * @param string $host
     */
    public function __construct($emailAddress, $password, $host)
    {
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->host = $host;
    }

    public function getAddBody()
    {
        return [
            'account_type' => 'imap',
            'account' => $this->emailAddress,
            'password' => $this->password,
            'host' => $this->host
        ];
    }
}
?>
