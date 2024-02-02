<?php


use Illuminate\Console\Command;
use App\Models\McqQuestion;
use App\Helpers\Helper;

class SendMcqNotifications extends Command
{
    protected $signature = 'notifications:sendMcq';
    protected $description = 'Send MCQ notifications';

    public function handle()
    {
        // Get MCQ questions for today
        $todayQuestions = McqQuestion::whereDate('mcq_date', now()->toDateString())->get();

        foreach ($todayQuestions as $question) {
            // Send notification for each question
            $userRecords = // Get the user records for this question (you may need to adjust this based on your application logic)
            $message = strip_tags($question->mcq_question);

            // Send notification using your helper method
            Helper::notifyScheduleClass($userRecords, $message);
        }

        $this->info('MCQ notifications sent successfully.');
    }
}
