<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AttendanceRow extends Component
{
    public $attendance;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($attendance = null)
    {

        $this->attendance = $attendance;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.attendance-row');
    }
}
