<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

use JMS\Serializer\Annotation\Type;

class State
{
    /**
     * @var Site[]
     * @Type("array<Sonro\Checkup\Domain\Model\Site>")
     */
    public array $upSites = [];

    /**
     * @var Site[]
     * @Type("array<Sonro\Checkup\Domain\Model\Site>")
     */
    public array $downSites = [];
}
