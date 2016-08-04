# API
This page contains information on the SDK's classes and methods.

## Class SiftApi
A client for EasilyDo's Sift API. Sift is an Artificial Intelligence for Email
that creates relevant features for your users with an email parse API that
simplifies and understands mail.

You can read more about [Sift here](http://sift.easilydo.com/).

##### Parameters
- `$apiKey`: `String` The API Key can be found from the developer's dashboard.
- `$apiSecret`: `String` The API Secret can be found from the developer's
dashboard.
- `$guzzleClient`: `GuzzleHttp\Client` (Optional) The guzzle client through
which requests are made. The `base_uri` config of this client must be set to
`https://api.easilydo.com`.

##### Example
Using the default guzzle client.
```php
use Easilydo\SiftApi;

$sift = new SiftApi('api_key', 'api_secret');
```

Using a custom guzzle client.
```php
use Easilydo\SiftApi;

$client = new \GuzzleHttp\Client(['base_uri' => SiftApi::API_URL]);
$sift = new SiftApi('api_key', 'api_secret', $client);
```

## Methods
- [discovery](#discovery)
- [addUser](#adduser)
- [deleteUser](#deleteuser)
- [getEmailConnections](#getemailconnections)
- [addEmailConnection](#addemailconnection)
- [deleteEmailConnection](#deleteemailconnection)
- [getSifts](#getsifts)
- [getSift](#getsift)
- [getConnectToken](#getconnecttoken)
- [getConnectEmailUrl](#getconnectemailurl)
- [sendFeedback](#sendfeedback)

#### discovery
Returns the parsed eml file as described
[here](https://sift.easilydo.com/sift/documentation#sifts-endpoint-list) as
an `array`.

##### Parameters
- `$email`: (`String`) The contents of the eml file

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
$email = file_get_contents('test_file.eml');
$email = trim($email);

try {
  $response = $sift->discovery($email);
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### addUser
Adds a new user with the given username. The add user API will automatically
create an account if it doesn't exist.

##### Parameters
- `$locale`: `String` The locale of the new user (e.g. en_US).
- `$username`: `String` The username of the new user.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->addUser('en_US', 'testuser');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### deleteUser
Removes a user with the given username.

##### Parameters
- `$username`: `String` The username of the user to be removed.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->deleteUser('testuser');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### getEmailConnections
Get all email connections linked to the user account.

##### Parameters
- `$username`: `String` The username of the user to obtain the email connections
from.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->getEmailConnections('testuser');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### addEmailConnection
Adds a new email connection to the user account.

##### Parameters
- `$username`: `String` The username of the user to add the email connection to.
- `$connection`: `Easilydo\EmailConnections\Connection` The connection to be
added. Valid connection types can be found [here](#connections).

##### Throws
- `Easilydo\Exceptions\SiftApiException` if `$connection` is not an instance of
`Easilydo\EmailConnections\Connection`.
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
$google = new \Easilydo\EmailConnections\GoogleConnection('123@abc.com', 'abc');

try {
  $response = $sift->addEmailConnection('testuser', $google);
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### deleteEmailConnection
Deletes an email connection from the given user.

##### Parameters
- `$username`: `String` The username of the user to delete the email connection
from.
- `$connectionId`: `String` The connection to be removed.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->deleteEmailConnection('testuser', '123');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### getSifts
Get all Sifts(tasks) from the user.

##### Parameters
- `$username`: `String` The username of the user to get the Sifts from.
- `$limit`: `Integer` The maximum number of results to return. (Defaults to 100)
- `$offset`: `Integer` Start the list at this offset (zero-based) (Defaults to
0)
- `$lastUpdateTime`: `Integer` Epoch timestamp. Returns results with last update
time greater than (that is, more recent than) the specified time.
- `$domains`: `Array` A list of domains. The domain should be one of the
following: flight, hotel, rentalcar, train, boardingpass, shipment, contact,
purchase, reservation, event, deal, bill. If omited, this api will return the
sifts with all domains.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->getSifts('testuser', 10, 0, 0, ['flight']);
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### getSift
Get all Sifts(tasks) from the user.

##### Parameters
- `$username`: `String` The username of the user to get the Sift from.
- `$siftId`: `Integer` The ID of the Sift to get from the user.
- `$includeEml`: `Boolean` Indicates if an eml MIME file is wanted together with
the payload. (Defaults to false)

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->getSift('testuser', 101);
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### getConnectToken
Get a new connect token for specific user.

##### Parameters
- `$username`: `String` The username of the user to get the token for.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
try {
  $response = $sift->getConnectToken('testuser');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```

#### getConnectEmailUrl
Gets a URL to the Email Connection webpage that allows a user to connect an
email account to Sift.

##### Parameters
- `$username`: `String` The username of the user to get the token for.
- `$redirectUrl`: `String` The url to redirect the user to after making the
connection. (Optional)
- `$token`: `String` The connect token that should be generated by calling
`getConnectToken`. (If not provided, `getConnectToken` will be called to
retrieve a new token)

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request for the connect
token fails.

##### Example
```php
try {
  $url = $sift->getConnectEmailUrl('testuser', 'https://test.com/');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}
```

#### sendFeedback
Gives feedback to the EasilyDo team.

##### Parameters
- `$email`: `String` The contents of the eml file (similar to that of
  [discovery](#discovery)).
- `$locale`: `String` The locale of the email (e.g. en_US).
- `$timezone`: `String` The timezone of the email, e.g. America/Los_Angeles.

##### Throws
- `Easilydo\Exceptions\SiftRequestException` if the request fails.

##### Example
```php
$email = file_get_contents('test_file.eml');
$email = trim($email);

try {
  $response = $sift->sendFeedback($email, 'en_US', 'America/Los_Angeles');
} catch (Easilydo\Exceptions\SiftRequestException $e) {
  echo $e->getMessage();
  exit;
}

$result = $response['result'];
```
## Connections
- [ExchangeConnection](#exchangeconnection)
- [GoogleConnection](#googleconnection)
- [ImapConnection](#imapconnection)
- [MicrosoftConnection](#microsoftconnection)
- [YahooConnection](#yahooconnection)

### ExchangeConnection
##### Parameters
- `$emailAddress`: `String` Email address for the Exchange account
- `$password`: `String` Password for the Exchange account
- `$host`: `String` Host for the Exchange account (Optional)
- `$account`: `String` Username for the Exchange account (Optional)

### GoogleConnection
##### Parameters
- `$emailAddress`: `String` The email address associated with the Google account
- `$refreshToken`: `String` The refresh token for the OAuth2 connection

### ImapConnection
##### Parameters
- `$emailAddress`: `String` The email address for the IMAP account
- `$password`: `String` The password for the IMAP account
- `$host`: `String` The host for the IMAP account

### MicrosoftConnection
##### Parameters
- `$emailAddress`: `String` The email address associated with the Live account
- `$refreshToken`: `String` The refresh token for the OAuth2 connection
- `$redirectUri`: `String` The redirect URI that was used for the OAuth2
connection

### YahooConnection
##### Parameters
- `$account`: `String` The Yahoo GUID associated with the user’s Yahoo account
- `$refreshToken`: `String` The refresh token for the OAuth2 connection
- `$redirectUri`: `String` The redirect URI that was used for the OAuth2
connection
