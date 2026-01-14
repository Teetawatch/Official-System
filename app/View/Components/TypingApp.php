<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TypingApp extends Component
{
    /**
     * The user role.
     *
     * @var string
     */
    public string $role;

    /**
     * The page title.
     *
     * @var string
     */
    public string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(string $role = 'student', string $title = 'ระบบวิชาพิมพ์หนังสือราชการ 1')
    {
        $this->role = $role;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.typing-app');
    }
}
