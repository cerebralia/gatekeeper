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

namespace FlameCore\Gatekeeper\Result;

/**
 * This class represents a positive result.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class PositiveResult extends AbstractResult
{
    /**
     * The result code
     *
     * @var string|null
     */
    protected $code;

    /**
     * Creates a PositiveResult object.
     *
     * @param string[] $reporting List of reporting Check classes
     * @param string $code The result code
     */
    public function __construct(array $reporting = [], $code = null)
    {
        parent::__construct($reporting);

        $this->code = $code;
    }

    /**
     * Gets the result code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }
}
