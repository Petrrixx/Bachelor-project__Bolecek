<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public ?UploadedFile $attachment;

    /**
     * Vytvorí novú inštanciu správy.
     *
     * @param  array  $data  -- všetky vyplnené polia formulára
     * @param  UploadedFile|null  $attachment  -- priložený súbor alebo null
     */
    public function __construct(array $data, ?UploadedFile $attachment = null)
    {
        $this->data = $data;
        $this->attachment = $attachment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [ new Address($this->data['email'], $this->data['name'].' '.$this->data['surname']) ],
            subject: 'Nová správa z kontaktného formulára'
        );
    }

    /**
     * Definícia obsahu (markdown view).
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.contact.form',
            with: [
                'name'    => $this->data['name'].' '.$this->data['surname'],
                'phone'   => $this->data['phone'],
                'email'   => $this->data['email'],
                'subject' => $this->data['subject'],
                'body'    => $this->data['message'],
            ]
        );
    }

    /**
     * Prílohy správy.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (! $this->attachment) {
            return [];
        }

        return [
            Attachment::fromPath($this->attachment->getRealPath())
                ->as($this->attachment->getClientOriginalName())
                ->withMime($this->attachment->getMimeType()),
        ];
    }
}
