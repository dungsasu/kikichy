<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ButtonAction extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $create;
    public $model;
    public $controller;
    public $show;
    public $viewCurrent;

    public function __construct($create = '', $model = null, $show = '', $view = '', $controller = null)
    {
        $this->create = $create;
        $this->model = $model;
        $this->show = $show;
        $this->viewCurrent = $view;
        $this->controller = $controller;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button-action');
    }
}
