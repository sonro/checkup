<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain;

interface UrlTesterInterface
{
    /**
     * @param string[] $urlList
     * @return array
     */
    public function testList(array $urlList): array;

    public function testOne(string $url): bool;
}
