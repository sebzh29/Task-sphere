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
class SelectIssueAssignee
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public \App\Entity\Issue $issue;

    /** @var User[]  */
    #[LiveProp]
    public array $people = [];


    #[LiveProp(writable: true)]
    public User $assignee;

    #[LiveAction]
    public function updateAssignee(
        EntityManagerInterface $em
    )
    {
       $this->validate();

         $this->issue->setAssignee($this->assignee);

            $em->flush();
    }

}