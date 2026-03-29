<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AttendanceDateTable extends Component
{
    public $attendances;
    public $dates;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($attendances, $dates)
    {

        $this->attendances = $attendances;
        $this->dates = $dates;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.attendance-date-table');
    }
}
