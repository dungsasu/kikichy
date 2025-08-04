<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Filter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $keyword;
    public $prefix;
    public $filterValue;
    public $categories;

    public function __construct($keyword = null, $prefix = null, $filterValue = null, $categories = null)
    {
        $this->keyword = $keyword;
        $this->prefix = $prefix;
        $this->filterValue = $filterValue;
        $this->categories = $categories;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filter');
    }
}
