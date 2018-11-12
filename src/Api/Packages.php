<?php

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Packages extends AbstractApi
{
    /**
     * Packages that are mirrored from a public Composer repository like packagist.org.
     */
    const ORIGIN_PUBLIC_PROXY = 'public-proxy';

    /**
     * Packages that are mirrored from a private Composer repository requiring authentication like repo.magento.com.
     */
    const ORIGIN_PRIVATE_PROXY = 'private-proxy';

    /**
     * All other packages from a VCS repository or a custom JSON definition.
     */
    const ORIGIN_PRIVATE = 'private';

    public function all(array $filters = [])
    {
        $availableOrigins = [self::ORIGIN_PUBLIC_PROXY, self::ORIGIN_PRIVATE_PROXY, self::ORIGIN_PRIVATE];
        if (isset($filters['origin']) && !in_array($filters['origin'], $availableOrigins, true)) {
            throw new InvalidArgumentException('Filter "origin" has to be one of: "' . implode('", "', $availableOrigins) . '".');
        }

        return $this->get('/packages/', $filters);
    }

    public function show($packageName)
    {
        return $this->get(sprintf('/packages/%s/', $packageName));
    }

    public function createVcsPackage($url, $credentials = null)
    {
        return $this->post('/packages/', ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentials]);
    }

    public function createCustomPackage($customJson, $credentials = null)
    {
        return $this->post('/packages/', ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentials]);
    }

    public function updateVcsPackage($packageName, $url, $credentials = null)
    {
        return $this->put(sprintf('/packages/%s/', $packageName), ['repoType' => 'vcs', 'repoUrl' => $url, 'credentials' => $credentials]);
    }

    public function updateCustomPackage($packageName, $customJson, $credentials = null)
    {
        return $this->put(sprintf('/packages/%s/', $packageName), ['repoType' => 'package', 'repoConfig' => $customJson, 'credentials' => $credentials]);
    }

    public function remove($packageName)
    {
        return $this->delete(sprintf('/packages/%s/', $packageName));
    }

    public function listCustomers($packageName)
    {
        return $this->get(sprintf('/packages/%s/customers/', $packageName));
    }
}
