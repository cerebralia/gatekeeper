<?php
/**
 * FlameCore Gatekeeper
 * Copyright (C) 2015 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Gatekeeper
 * @version  0.1-dev
 * @link     http://www.flamecore.org
 * @license  http://opensource.org/licenses/ISC ISC License
 */

namespace FlameCore\Gatekeeper\Tests\Check;

use FlameCore\Gatekeeper\Check\CheckInterface;
use FlameCore\Gatekeeper\Check\SpambotsBlacklistCheck;

/**
 * Test class for SpambotsBlacklistCheck
 */
class SpambotsBlacklistCheckTest extends CheckTestCase
{
    protected function setUp()
    {
        $this->check = new SpambotsBlacklistCheck();
    }

    public function testCheckPositiveSpambotNamesBeginning()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => '8484 Boston Project']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckPositiveSpambotNamesAnywhere()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => 'foo bar <script></script>']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckPositiveSpambotNamesRegex()
    {
        $result = $this->runTestCheck('/', null, [], [], [], ['HTTP_USER_AGENT' => 'MSIE 2']);

        $this->assertEquals('17f4e8c8', $result);
    }

    public function testCheckNegative()
    {
        $result = $this->runTestCheck();

        $this->assertEquals(CheckInterface::RESULT_OKAY, $result);
    }
}
