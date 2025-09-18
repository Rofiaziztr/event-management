<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DatePickerAllPast extends Component
{
    public function __construct(
        public string $name,
        public string $id,
        public ?string $value = '',
        public string $placeholder = 'Pilih Tanggal'
    ) {
        //
    }
    public function render()
    {
        return view('components.date-picker-all-past');
    }
}
