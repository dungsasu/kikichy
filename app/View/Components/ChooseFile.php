<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ChooseFile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $dataComponent;
    public $title;
    public $type;
    public $field;
    public $class;
    public $name;
    public $readonly;

    public function __construct($id = '', $name = '', $dataComponent = null,  $type = '', $title = '', $field = 'url', $class = '', $readonly = true)
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
        $this->field = $field;
        $this->class = $class;
        $this->name = $name ? $name : $id;
        $this->dataComponent = $dataComponent ?? new \stdClass();
        $this->readonly = $readonly;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.choose-file');
    }
}
