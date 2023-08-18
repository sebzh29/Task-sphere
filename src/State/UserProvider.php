<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ('me' === $operation->getName()) {
            return $this->security->getUser();
        }

        return [];
    }
}