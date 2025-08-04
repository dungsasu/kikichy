<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CreateLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dataComponent;
    public $name;

    public function __construct($dataComponent, $name = '')
    {
        $this->dataComponent = $dataComponent;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.create-link');
    }
}
