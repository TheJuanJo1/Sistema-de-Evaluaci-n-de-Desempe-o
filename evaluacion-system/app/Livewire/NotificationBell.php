<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $notifications = [];
    public $showPanel = false;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
        $this->unreadCount = $this->notifications->count();
    }

    public function loadNotifications()
    {
        $this->notifications = Auth::user()->unreadNotifications()->take(20)->get();
        $this->unreadCount = $this->notifications->count();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        // Refresh the list
        $this->loadNotifications();
        $this->unreadCount = $this->notifications->count();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
