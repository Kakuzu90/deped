<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Widget extends Component
{
    public $title;
    public $count;
    public $icon;
    public $color;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, int $count, string $icon, string $color)
    {
        $this->title = $title;
        $this->count = $count;
        $this->icon = $icon;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget');
    }
}
