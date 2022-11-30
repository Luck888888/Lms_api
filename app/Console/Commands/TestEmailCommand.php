<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:notification:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email to an email address';

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
        $email = $this->argument('email');
        $this->alert('Sending test email to ' . $email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email ' . $email . ' invalid');
            return 0;
        }

        try {
            Mail::raw("Test email. Say hello.", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test email from CRM');
            });
            $this->info('Email was sent.');
        } catch (\Exception | \Error $e) {
            $this->error('Email was not sent.');
            $this->error($e->getMessage());
        }


        return 0;
    }
}
