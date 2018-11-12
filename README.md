# Private Packagist API Client

## Requirements

* PHP >= 5.6
* [Guzzle](https://github.com/guzzle/guzzle) library,

## Install

Via Composer:

```bash
$ composer require private-packagist/api-client php-http/guzzle6-adapter
```

Why do you need to require `php-http/guzzle6-adapter`? We are decoupled from any HTTP messaging client with help by [HTTPlug](http://httplug.io/), so you can pick an HTTP client of your choice, guzzle is merely a recommendation.

## Basic usage of `private-packagist/api-client` client

```php
<?php

// This file is generated by Composer
require_once __DIR__ . '/vendor/autoload.php';

$client = new \PrivatePackagist\ApiClient\Client();
$client->authenticate('api-token', 'api-secret');
$packages = $client->packages()->all();
```

From `$client` object, you can access the full Private Packagist API.

## Documentation

Full documentation can be found in the [Private Packagist documentation](https://packagist.com/docs/api).

#### Organization

##### Trigger a full synchronization
```php
$jobs = $client->organization()->sync();
```
Returns an array of created jobs. One for every synchronization.

#### Customer

##### List an organization's customers
```php
$customers = $client->customers()->all();
```
Returns an array of customers.

##### Show a customer
```php
$customerId = 42;
$customer = $client->customers()->show($customerId);
```
Returns a single customer.

##### Create a customer
```php
$customer = $client->customers()->create('New customer name');
```
Returns the customer.

##### Delete a customer
```php
$customerId = 42;
$client->customers()->remove($customerId);
```

##### List a customer's packages
```php
$customerId = 42;
$packages = $client->customers()->listPackages($customerId);
```
Returns an array of customer packages.

##### Grant a customer access to a package or update the limitations
```php
$customerId = 42;
$packages = [
    [
        'name' => 'acme-website/package',
        'versionConstraint' => '^1.0 | ^2.0', // optional version constraint to limit updades the customer receives
        'expirationDate' => (new \DateTime())->add(new \DateInterval('P1Y'))->format('c'), // optional expiration date to limit updades the customer receives
    ],
];
$packages = $client->customers()->addOrUpdatePackages($customerId, $packages);
```
Returns an array of added customer packages.

##### Revoke access to a package from a customer
```php
$customerId = 42;
$packageName = 'acme-website/package';
$client->customers()->removePackage($customerId, $packageName);
```

#### Regenerate a customer's Composer repository token
```php
$customerId = 42;
$confirmation = [
    'IConfirmOldTokenWillStopWorkingImmediately' => true,
];
$composerRepository = $client->customers()->regenerateToken($customerId, $confirmation);
```
Returns the updated Composer repository.

#### Package

##### List an organization's packages
```php
$filters = [
    'origin' => \PrivatePackagist\ApiClient\Api\Packages::ORIGIN_PRIVATE, // optional filter to only receive packages that can be added to customers 
];
$packages = $client->packages()->all($filters);
```
Returns an array of packages.

##### Show a package
```php
$package = $client->packages()->show('acme-website/package');
```
Returns the package.

##### Create a vcs package
```php
$job = $client->packages()->createVcsPackage('https://github.com/acme-website/package');
```
Returns a new job.

##### Create a vcs package with credentials
```php
$credentialId = 42;
$job = $client->packages()->createVcsPackage('https://github.com/acme-website/package', $credentialId);
```
Returns a new job.

##### Create a custom package
```php
$packageDefinition = '{...}'
$job = $client->packages()->createCustomPackage($packageDefinition);
```
Returns a new job.

##### Create a custom package with credentials
```php
$packageDefinition = '{...}'
$credentialId = 42;
$job = $client->packages()->createCustomPackage($packageDefinition, $credentialId);
```
Returns a new job.

##### Update a vcs package
```php
$job = $client->packages()->updateVcsPackage('acme-website/package', 'https://github.com/acme-website/package');
```
Returns a new job.

##### Update a custom package
```php
$packageDefinition = '{...}'
$job = $client->packages()->updateCustomPackage('acme-website/package', $packageDefinition);
```
Returns a new job.

##### Delete a package
```php
$client->packages()->remove('acme-website/package');
```

##### List all customers with access to a package
```php
$client->packages()->listCustomers('acme-website/package');
```
Returns a list of customers with access to the package.

#### Credential

##### List an organization's credentials
```php
$packages = $client->credentials()->all();
```
Returns an array of credentials.

##### Show a credential
```php
$credentialId = 42;
$credential = $client->credentials()->show($credentialId);
```
Returns the credential.

##### Create a credential
```php
$type = \PrivatePackagist\ApiClient\Api\Credentials::TYPE_HTTP_BASIC;
$credential = $client->credentials()->create('ACME http auth', $type, 'acme-website.com', 'username', 'password');
```
Returns the new credential.

##### Update a credential
```php
$credentialId = 42;
$type = \PrivatePackagist\ApiClient\Api\Credentials::TYPE_HTTP_BASIC;
$credential = $client->credentials()->update($credentialId, $type, 'username', 'password');
```
Returns the updated credential.

##### Delete a credential
```php
$credentialId = 42;
$client->credentials()->remove($credentialId);
```

#### Job

##### Show a job
```php
$job = $client->jobs()->show($jobId);
```
Returns the job.

## License

`private-packagist/api-client` is licensed under the MIT License
