<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $pagename;
    public $pagetitle;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pagename,$pagetitle){
        $this->pagename =config("constants.".$pagename);
        $this->pagetitle =config("constants.".$pagetitle);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumbs');
    }
}
