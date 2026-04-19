<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

#[Signature('app:hash-nisn')]
#[Description('Command description')]
class HashNisn extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = Student::all();

        foreach ($students as $s) {
            if (!$s->nisn_hash) {
                $s->nisn_hash = Hash::make($s->nisn);
                $s->save();
            }
        }

        $this->info('Semua NISN berhasil di-hash');
    }
}
