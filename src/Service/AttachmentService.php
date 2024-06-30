<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class AttachmentService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        #[Autowire('%absolute_attachments_directory%')] private readonly string $absoluteAttachmentsDirectory,
        #[Autowire('%attachments_directory%')] private readonly string $attachmentsDirectory,
    )
    {
    }

    public function handleUploadedAttachment(
        Issue $issue,
        Request $request
    ): ?Attachment
    {
        /** @var ?UploadedFile $attachmentFile */
        $attachmentFile = $request->files->get('attachment');

        if (!$attachmentFile) {
            return null;
        }

        $fileName = $this->uniqueFilename($attachmentFile);

        $attachment = new Attachment();
        $attachment->setIssue($issue);
        $attachment->setOriginalName($attachmentFile->getClientOriginalName());
        $attachment->setPath($this->absoluteAttachmentsDirectory . DIRECTORY_SEPARATOR . $fileName);

        $attachmentFile->move($this->attachmentsDirectory, $fileName);

        $this->em->persist($attachment);
        $this->em->flush();

        return $attachment;
    }

    private function uniqueFilename(UploadedFile $file): string
    {
        return (uniqid(more_entropy: true)) . '.' . $file->guessExtension();
    }

    public function delete(Attachment $attachment)
    {
        $filename = $this->attachmentsDirectory
            . DIRECTORY_SEPARATOR
            . pathinfo($attachment->getPath(), PATHINFO_FILENAME . '.'
            . pathinfo($attachment->getPath(), PATHINFO_EXTENSION));

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

}