<?php

namespace WirelessLogic;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct(iterable $commands = [])
    {
        foreach ($commands as $command) {
            $this->add($command);
        }

        parent::__construct('Web Scraper', '1.0.0');
    }
}
