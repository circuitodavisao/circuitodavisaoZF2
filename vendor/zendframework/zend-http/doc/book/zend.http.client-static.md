# HTTP Client - Static Usage

## Overview

The `Zend\Http` component also provides `Zend\Http\ClientStatic`, a static HTTP client which exposes
a simplified API for quickly performing GET and POST operations:

## Quick Start

```php
use Zend\Http\ClientStatic;

// Simple GET request
$response = ClientStatic::get('http://example.org');

// More complex GET request, specifying query string 'foo=bar' and adding a
// custom header to request JSON data be returned (Accept: application/json)
$response = ClientStatic::get(
    'http://example.org',
    array('foo' => 'bar'),
    array('Accept' => 'application/json')
);

// We can also do a POST request using the same format.  Here we POST
// login credentials (username/password) to a login page:
$response = ClientStatic::post('https://example.org/login.php', array(
    'username' => 'foo',
    'password' => 'bar',
));
```

## Available Methods

**get**  
`get(string $url, array $query = array(), array $headers = array(), mixed $body = null,
$clientOptions = null)`

Perform an HTTP `GET` request using the provided URL, query string variables, headers and request
body. The fifth parameter can be used to pass configuration options to the HTTP Client instance.

Returns Zend\\Http\\Response

<!-- -->

**post**  
`post(string $url, array $params, array $headers = array(), mixed $body = null, $clientOptions =
null)`

Perform an HTTP `POST` request using the provided URL, parameters, headers and request body. The
fifth parameter can be used to pass configuration options to the HTTP Client instance.

Returns Zend\\Http\\Response


