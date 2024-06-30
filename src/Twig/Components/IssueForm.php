<?php

namespace App\Twig\Components;

use App\Entity\Issue;

use App\Form\Type\IssueType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class IssueForm extends AbstractController
{
    use ComponentToolsTrait;
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp]
    public ?Issue $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        $this->initialFormData ??= new Issue();

        return $this->createForm(IssueType::class, $this->initialFormData);
    }
    #[LiveAction]
    public function save(
        EntityManagerInterface $em
    ): Response
    {
        $this->validate();

        $this->submitForm();

        /** @var Issue $issue */
        $issue = $this->form->getData();

        $em->persist($issue);
        $em->flush();

        return $this->redirectToRoute('issue_show', [
            'id' => $issue->getId()
        ]);
    }
}