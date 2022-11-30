<?php

namespace Modules\Users\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanReseatTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:clean:reset_tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an expired reset token.';

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
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Clear reset tokens.');

        $reset_password_expire = config('users.reset_password.expire');

        DB::table('password_resets')
            ->where('created_at', '<=', Carbon::now()->subMinutes($reset_password_expire)->toDateTimeString())
            ->delete();

        $this->info('All deleted successfully!');
    }
}
