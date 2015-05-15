<?php
/**
 * Webtools Library
 * Copyright (C) 2014 IceFlame.net
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE
 * FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER
 * IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING
 * OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 *
 * @package  FlameCore\Webtools
 * @version  1.2
 * @link     http://www.flamecore.org
 * @license  ISC License <http://opensource.org/licenses/ISC>
 */

namespace FlameCore\Gatekeeper\Tests\Check;

use FlameCore\Gatekeeper\Visitor;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test case for Check classes
 */
abstract class CheckTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FlameCore\Gatekeeper\Check\CheckInterface
     */
    protected $check;

    /**
     * @param string $uri
     * @param string $method
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string $content
     * @return int|string
     */
    protected function runTestCheck($uri = '/', $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $request = Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
        return $this->runCustomTestCheck($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return int|string
     */
    protected function runCustomTestCheck(Request $request)
    {
        $visitor = new Visitor($request);
        return $this->check->checkVisitor($visitor);
    }
}
