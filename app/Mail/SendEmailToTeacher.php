<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailToTeacher extends Mailable
{
    use Queueable, SerializesModels;

   public $to;
    public $url;
    public $subject;
    public $teacherName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
       public function __construct($to, $url, $subject, $teacherName)
    {
        $this->to = $to;
        $this->url = $url;
        $this->subject = $subject;
        $this->teacherName = $teacherName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.teacher_email')
                    ->with([
                        'url' => $this->url,
                        'teacherName' => $this->teacherName,
                    ]);
                    
                    
    }
}
