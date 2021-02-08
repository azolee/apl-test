<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The item to insert as User.
     *
     * @var array
     */
    protected $item;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::withTrashed()->firstOrNew([
            'email' => $this->item['email']
        ], [
            'name' => $this->item['name'],
            'phone' => $this->item['phone'],
        ]);

        // not fillable fields has to be updated manually
        $user->email = $this->item['email'];
        $user->password = $this->item['password'];

        $user->save();

        if ($this->item['deleted']) {
            $user->delete();
        }
    }
}
