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

namespace FlameCore\Gatekeeper\Check;

use FlameCore\Gatekeeper\Visitor;

/**
 * Class PostRequestCheck
 *
 * @author   Michael Hampton <bad.bots@ioerror.us>
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class PostRequestCheck implements CheckInterface
{
    /**
     * The settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * @param array $settings The settings
     */
    public function __construct(array $settings = [])
    {
        $defaults = array(
            'offsite_forms' => false
        );

        $this->settings = array_replace($defaults, $settings);
    }

    /**
     * {@inheritdoc}
     */
    public function checkVisitor(Visitor $visitor)
    {
        if ($visitor->getRequestMethod() == 'POST') {
            if ($result = $this->checkPostRequest($visitor)) {
                return $result;
            }
        }

        return CheckInterface::RESULT_OKAY;
    }

    /**
     * Analyzes POST requests.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkPostRequest(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();
        $data = $visitor->getRequestData();

        // MovableType needs specialized screening
        if (stripos($visitor->getUserAgent()->getUserAgentString(), 'MovableType') !== false) {
            if (strcmp($headers->get('Range'), 'bytes=0-99999')) {
                return '7d12528e';
            }
        }

        // Trackbacks need special screening
        if ($data->has('title') && $data->has('url') && $data->has('blog_name')) {
            return $this->checkTrackback($visitor);
        }

        // Catch a few completely broken spambots
        foreach ($data->all() as $key => $value) {
            if (strpos($key, "\tdocument.write") !== false) {
                return 'dfd9b1ae';
            }
        }

        // If 'Referer' exists, it should refer to a page on our site
        if (!$this->settings['offsite_forms'] && $headers->has('Referer')) {
            $url = parse_url($headers->get('Referer'));
            $url['host'] = preg_replace('|^www\.|', '', $url['host']);
            $host = preg_replace('|^www\.|', '', $headers->get('Host'));
            if (strcasecmp($host, $url['host'])) {
                return 'cd361abb';
            }
        }

        return false;
    }

    /**
     * Analyzes trackbacks.
     *
     * @param \FlameCore\Gatekeeper\Visitor $visitor
     * @return bool|string
     */
    protected function checkTrackback(Visitor $visitor)
    {
        $headers = $visitor->getRequestHeaders();

        // Web browsers don't send trackbacks
        if ($visitor->isBrowser()) {
            return 'f0dcb3fd';
        }

        // Proxy servers don't send trackbacks either
        if ($headers->has('Via') || $headers->has('Max-Forwards') || $headers->has('X-Forwarded-For') || $headers->has('Client-Ip')) {
            return 'd60b87c7';
        }

        // Real WordPress trackbacks may contain 'Accept:' and have a charset defined
        if (strpos($visitor->getUserAgent()->getUserAgentString(), 'WordPress/') !== false) {
            if (strpos($headers->get('Accept'), 'charset=') === false) {
                return 'e3990b47';
            }
        }

        return false;
    }
}
