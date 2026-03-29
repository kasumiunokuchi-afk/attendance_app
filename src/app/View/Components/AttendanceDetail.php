<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AttendanceDetail extends Component
{

    public $stampCorrection;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($stampCorrection)
    {
        $this->stampCorrection = $stampCorrection;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.attendance-detail');
    }
}
