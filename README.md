# php-curl
php curl class


## easy way to custom useragent

```php
define('PHPCURL_UA', 'your ua');
```

## easy way to custom cookie

```php
define('PHPCURL_COOKIE', 'your cookie');
```

## use sample

```php
use \Lizus\PHPCurl\PHPCurl;

$curl=new PHPCurl();
$data=$curl->get('some url');
var_dump($data);
```
