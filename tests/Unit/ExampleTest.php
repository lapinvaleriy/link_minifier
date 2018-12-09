<?php

namespace Tests\Unit;

use App\Models\Link;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
//        $this->assertTrue(true);

        $link = new Link;
        var_dump($link);
    }
}
