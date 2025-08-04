<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Related extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $dataComponent;
    public $name;
    public $title;
    public $categories;
    public $routeAjax;
    public $dataTable;

    public function __construct($dataComponent = [], $categories = [], $name = '', $routeAjax = '', $title = '', $dataTable = [])
    {
        $this->dataComponent = $dataComponent;
        $this->name = $name;
        $this->categories = $categories;
        $this->routeAjax = $routeAjax;
        $this->title = $title;
        $this->dataTable = $dataTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.related');
    }
}
