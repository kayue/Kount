# Kount PHP library

## Example

```php
<?php

use Kayue\Kount\Kount;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$kount = new Kount('API_KEY');

// Get an email's VIP status (approve / decline)
$kount->getEmail('someone@example.com')->getResult();

// Update an trasacntion's approve status
$kount->updateOrderStatus('TXID', Kount::ORDER_STATUS_APPROVE);
```
