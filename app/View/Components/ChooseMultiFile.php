<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ChooseMultiFile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dataComponent;
    public $name;
    public $type;


    public function __construct($dataComponent = null, $name = '', $type = '')
    {
        $this->dataComponent = $dataComponent ?? new \stdClass();
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.choose-multi-file');
    }
}
