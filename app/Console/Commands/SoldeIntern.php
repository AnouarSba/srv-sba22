<?php

namespace App\Console\Commands;

use App\Models\Control;
use App\Models\Kabid;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SoldeIntern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'solde:intern';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verification de solde intern';

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
        DB::table('spcarts')->update(array('cont' => 0));
       $kabids = Kabid::all();
        foreach ($kabids as $kabid) {
            $rsolde = $kabid->transactions()->sum('amount');
            if ($rsolde <> $kabid->balance) {
                $wallet = Wallet::where('holder_type',Kabid::class)->where('holder_id',$kabid->id)->first();
                $wallet->balance = $rsolde;
                $wallet->save();
            }
        }

        $kabids = Control::all();
        foreach ($kabids as $kabid) {
            $rsolde = $kabid->transactions()->sum('amount');
            if ($rsolde <> $kabid->balance) {
                $wallet = Wallet::where('holder_type',Control::class)->where('holder_id',$kabid->id)->first();
                $wallet->balance = $rsolde;
                $wallet->save();
            }
        }
        $email_data = array(
            'name' =>  config('etus.server.name'),
            'email' =>  'nouariaissa44@gmail.com',
            'pin'=>  '0',
            'srvname'=>config('etus.server.name')
        );
      Mail::send('emails.cron', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Cron Joub INtern ')
                ->from('contact@deeperTech.dz', $email_data['srvname']);
        });
        $this->info('Solde intern updated successfully');
        
    }
}
