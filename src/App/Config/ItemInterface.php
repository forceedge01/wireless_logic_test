<?php

declare(strict_types = 1);

namespace WirelessLogic\App\Config;

/**
 * Allows for different items to be extended by the WebRepository.
 */
interface ItemInterface
{
    public function getUrl(): string;

    public function getContainerSelector(): string;
}
