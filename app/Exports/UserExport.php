<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class UserExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $user;
    public function __construct($user)
    {
       $this->user=$user;
    }
    public function view(): View
    {
        return view('admin.exports.users', [
            'users' =>$this->user
        ]);
    }
}
