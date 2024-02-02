<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailToTeacher;
use App\Models\ScheduleClass;
use App\Models\Teacher;
use Carbon\Carbon;
use App\Helpers\EmailHelper; 

use Illuminate\Console\Command;

class SendEmailScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send_before_five_minutes';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email sucessfully.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $currentDateTime = Carbon::now();
    $currentDate = $currentDateTime->format('Y-m-d');
    $fiveMinutesAfter = $currentDateTime->addMinutes(5); // Calculate the time after 5 minutes
    $currentTimeFormatted = $currentDateTime->format('H:i');

    $teachers = ScheduleClass::where('date', $currentDate)
        ->where('start_time', $fiveMinutesAfter->format('H:i'))
        ->get();
       
    // $this->info($teachers); die;
        foreach ($teachers as $teacher) {
            
            $teacherRecord = Teacher::find($teacher->teacher_id);

            // if ($teacherRecord) {
            //     $to = $teacherRecord->email;
            //     $url = $teacher->zoom_link;
            //     $subject = "Mail from Auricle";
            //     Mail::to($to)->send(new SendEmailToTeacher($to, $url, $subject, $teacherRecord->name));
            // }
            //start temprory mail code

           /* $body = '<p>This is a reminder for your live class starting soon.</p>Your Zoom link is: ' . $teacher->zoom_link . ' Please do not share it with anybody.';
            $details = [
                'name' => 'Auricle',
                'body' => $body
            ];

            $to = $teacher->email;
            $subject = 'Mail from Auricle';
            $message = '
                <html>
                <head>
                    <title>' . $teacherRecord->teacher_name . '</title>
                </head>
                <body>
                    <h1>' . $teacherRecord->teacher_name . '</h1>
                    <p>' . $details['body'] . '</p>
                </body>
                </html>
                ';

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: Auricle'; // Replace 'your_username' with the appropriate email account username
            
                   
            mail($to, $subject, $message, $headers);*/
             $emailResponse = EmailHelper::sendEmailWithCurlForReminder(
                    $teacher->email,
                    $teacherRecord->teacher_name,
                    $teacher->zoom_link,
                    "template_11_10_2023_10_10" // Replace with your template ID
                );  
            //end mail code
        }

        $this->info('Emails sent successfully!');


    }
}