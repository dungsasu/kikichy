<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Gallery extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dataComponent;
    public $name;
    public $type;
    public $field;
    public $index;

    public function __construct($dataComponent = [], $name = '', $type = '', $field = '', $index = 0)
    {
        $this->dataComponent = $dataComponent;
        $this->name = $name;
        $this->type = $type;
        $this->field = $field;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gallery');
    }
}
