<?php

namespace App\View\Components\client;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $breadcrumbs = array();
    public $current = '';

    public function __construct($breadcrumbs, $current)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->current = $current;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.client.breadcrumbs');
    }
}
