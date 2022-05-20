<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use Tests\TestCase;

class AffiliateTest extends TestCase
{
    public function testTwoPointsOnEarth()
    {
        $distance = Affiliate::twoPointsOnEarth(
            '53.3340285',
            '-6.2535495',
            '52.833502',
            '-8.522366'
        );

        $this->assertEquals(161.42059426515, $distance);
    }
}
