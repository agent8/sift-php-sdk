<?php
namespace Easilydo\EmailConnections;

/**
 * Class ExchangeConnection
 *
 * @package Easilydo
 */
class ExchangeConnection implements \Easilydo\EmailConnections\Connection
{
    /**
     * @var string The email address for the Exchange account
     */
    private $emailAddress;

    /**
     * @var string The password for the Exchange account
     */
    private $password;

    /**
     * @var string The host for the Exchange account
     */
    private $host;

    /**
     * @var string The username for the Exchange account
     */
    private $account;

    /**
     * Instantiates a new ExchangeConnection object
     *
     * @param string $emailAddress
     * @param string $password
     * @param string $host
     * @param string $account
     */
    public function __construct($emailAddress, $password, $account = NULL, $host = NULL)
    {
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->account = $account;
        $this->host = $host;
    }

    public function getAddBody()
    {
        $addBody = [
            'account_type' => 'exchange',
            'email' => $this->emailAddress,
            'password' => $this->password
        ];

        if ($this->host !== NULL) {
            $addBody['host'] = $this->host;
        }
        if ($this->account !== NULL) {
            $addBody['account'] = $this->account;
        }

        return $addBody;
    }
}
?>
