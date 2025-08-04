<?php

namespace App\View\Components\client;

use Illuminate\View\Component;

class CommentItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $item;
    public $className;

    public function __construct($item = null, $className = null)
    {
        $this->item = $item;
        $this->className = $className;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.client.comment-item');
    }
}
