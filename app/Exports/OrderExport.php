<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class OrderExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $order;
    public function __construct($order)
    {
       $this->order=$order;
    }
    public function view(): View
    {
        return view('admin.exports.orders', [
            'orders' =>$this->order
        ]);
    }
}
