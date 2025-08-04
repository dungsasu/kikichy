<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Editor extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $name;
    public $id;
    public $content;

    public function __construct($title = '', $name = '', $id = '', $content = '')
    {
        $this->title = $title;
        $this->name = $name;
        $this->id = $id;
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.editor');
    }
}
