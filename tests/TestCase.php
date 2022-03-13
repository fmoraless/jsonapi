<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use phpDocumentor\Reflection\Types\Parent_;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesJsonApiRequests;


}
