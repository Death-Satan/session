<?php

namespace DeathSatan\Session\Interfaces;

use SessionHandlerInterface;
use SessionIdInterface;
use SessionUpdateTimestampHandlerInterface;

/**
 * session驱动标准接口
 */
interface SessionHandle extends SessionIdInterface, SessionHandlerInterface, SessionUpdateTimestampHandlerInterface
{
    public function __construct(array $config = []);

    public function init();

    public function getDriver():Session;
}