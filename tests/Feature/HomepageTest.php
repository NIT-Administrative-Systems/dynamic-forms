<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function testUnauthenticatedUserIsRedirected()
    {
        $response = $this->get('/');
        $response->assertRedirect('/auth/login');
    }
}
