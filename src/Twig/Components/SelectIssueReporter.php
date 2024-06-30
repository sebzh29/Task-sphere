<?php

namespace App\Twig\Components;
use App\Entity\User;
use App\Enum\IssueType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class SelectIssueReporter
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var User[]  */
    #[LiveProp]
    public array $people = [];


    #[LiveProp(writable: true)]
    public User $reporter;

    #[LiveAction]
    public function updateReporter(
        EntityManagerInterface $em
    )
    {
       $this->validate();

         $this->issue->setReporter($this->reporter);

            $em->flush();
    }

}