<?php

namespace App\Jobs;

use App\UserAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use services\auth\EmailLogs;
use services\email_message_maker\MessageMaker;
use services\email_messages\ShareFileEmailMessage;
use services\email_services\EmailAddress;
use services\email_services\EmailBody;
use services\email_services\EmailMessage;
use services\email_services\EmailSender;
use services\email_services\EmailSubject;
use services\email_services\MailConf;
use services\email_services\PhpMail;
use services\email_services\SendEmailService;

class ShareFilesLinkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $linkDetails;
    protected $userId;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($linkDetails, $userId)
    {
        $this->linkDetails = $linkDetails;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $senderEmail = UserAccount::where('id', $this->userId)->first()['email'];
        $message = new ShareFileEmailMessage();
        $shareImagesMessage = $message->shareFileEmailMessage($senderEmail, $this->linkDetails['token']);
        $subject = new SendEmailService(new EmailSubject('[' . env('APP_NAME') . ']' . "  " . $senderEmail . "    has shared some files with you"));
        $mailTo = new EmailAddress($this->linkDetails['email']);
        $template =  str_ireplace('__appName__', env('APP_NAME'), file_get_contents('services/email-message-maker/message-template.html'));
        $messageMaker = new MessageMaker($template,$shareImagesMessage);
        $messageMakerTemplate = $messageMaker->make();
        $body = new EmailBody($messageMakerTemplate);
        $sendEmail = new EmailSender(new PhpMail(new MailConf(env('MAIL_HOST'), env('MAIL_USERNAME'), env('MAIL_PASSWORD'))));
        $result = $sendEmail->send(new EmailMessage($subject->getEmailSubject(), $mailTo, $body));
        $moduleName = 'Share Files Email Link';
        $emailLogs = new EmailLogs();
        $emailLogs->saveEmailLogs($moduleName, $mailTo->getEmail(), $subject->getEmailSubject()->getEmailSubject(), $body->getEmailBody());
        return ($result);
    }
}
