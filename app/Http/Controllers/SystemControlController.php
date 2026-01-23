<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SystemControlController extends Controller
{
    public function index(): View
    {
        $this->authorizeOwner();
        $isSystemActive = Setting::isSystemActive();
        $subscriptionStatus = Setting::get('subscription_status', 'active');
        $subscriptionExpiresAt = Setting::getSubscriptionExpiryDate();
        
        return view('system_control.index', compact('isSystemActive', 'subscriptionStatus', 'subscriptionExpiresAt'));
    }

    public function toggle(Request $request): RedirectResponse
    {
        $this->authorizeOwner();
        
        $newStatus = $request->input('status') === 'activate' ? 'true' : 'false';
        
        // Password protection for both activation and deactivation
        $request->validate([
            'system_password' => 'required|string'
        ]);

        if ($request->system_password !== '1301') {
            $action = $newStatus === 'false' ? 'deactivation' : 'activation';
            return redirect()->back()->with('error', "Incorrect password. System {$action} failed.");
        }

        Setting::set('system_active', $newStatus);

        $message = $newStatus === 'true' ? 'System successfully activated.' : 'System successfully deactivated.';
        $type = $newStatus === 'true' ? 'success' : 'warning';

        return redirect()->back()->with($type, $message);
    }

    public function updateSubscription(Request $request): RedirectResponse
    {
        $this->authorizeOwner();
        
        $request->validate([
            'expires_at' => 'required|date',
            'status' => 'required|in:active,expired',
        ]);

        Setting::set('subscription_expires_at', $request->expires_at);
        Setting::set('subscription_status', $request->status);

        return redirect()->back()->with('success', 'Subscription settings updated successfully.');
    }

    protected function authorizeOwner()
    {
        if (!auth()->user() || !auth()->user()->isOwner()) {
            abort(403, 'Unauthorized action. Only the System Owner can access this page.');
        }
    }
}
