<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\Ubayda\BusinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    protected $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }
    /**
     * =============================================
     * Display the login view.
     * =============================================
     */
    public function create(): View
    {
        $alerts = AlertHelper::getAlerts();
        return view('admin.auth.login', compact('alerts'));
    }

    /**
     * =============================================
     * Handle an incoming authentication request.
     * =============================================
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $business = $this->businessService->findLastSelectedBusiness(Auth::user()->id);
        Log::debug("isinya business terakhir adalah ", ["lastBusiness" => $business]);

        if (is_null($business)) {
            Log::info('No active business found, redirecting user to business selection', ['user_id' => Auth::user()->id]);
            return redirect(route('ubayda.business.user.index'));
        }
        else{

        }

        //set session
        Session::put('MY_ACTIVE_BUSINESS', $business->id);
        Session::put('MY_ACTIVE_BUSINESS_NAME', $business->name);
        Session::save();

        if(is_null($business)){
            return redirect(route('ubayda.business.user.index'));
        }
        else{
            return redirect()->intended(RouteServiceProvider::HOME);
        }


    }

    /**
     * =============================================
     * Destroy an authenticated session.
     * a.k.a Logout
     * =============================================
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
