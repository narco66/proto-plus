<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProtoLayout extends Component
{
    public $pageTitle;
    public $pageActions;
    public $breadcrumbs;

    public function __construct($pageTitle = null, $pageActions = null, $breadcrumbs = [])
    {
        $this->pageTitle = $pageTitle;
        $this->pageActions = $pageActions;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function render(): View
    {
        return view('layouts.proto', [
            'pageTitle' => $this->pageTitle,
            'pageActions' => $this->pageActions,
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }
}

