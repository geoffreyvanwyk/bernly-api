<?php

namespace App\Tests;

use App\Entity\Link;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectRequestTest extends WebTestCase
{
    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->links = $kernel
             ->getContainer()
             ->get('doctrine')
             ->getManager()
             ->getRepository(Link::class);

        $this->http = static::createClient();
    }

    /**
     * When the app receives an HTTP request containing a URL path that matches
     * the short alias of a long URL in the database, it should redirect the
     * request to the long URL.
     */
    public function testAppCanRedirectShortUrl(): void
    {
        // Arrange
        $link = $this->links->findOneBy([]);

        // Act
        $this->http->request('GET', $link->getAlias());

        // Assert
        $this->assertResponseRedirects($link->getUrl());
    }
}

