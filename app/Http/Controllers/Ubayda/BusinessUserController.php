<?php

namespace App\Http\Controllers\Ubayda;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Services\Ubayda\BusinessService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BusinessUserController extends Controller
{
    private $businessService;
    private $mainBreadcrumbs;
    private $userService;
    private $activeBusiness;

    public function __construct(BusinessService $businessService, UserService $userService)
    {
        $this->businessService = $businessService;
        $this->userService = $userService;

        // Store common breadcrumbs in the constructor
        $this->mainBreadcrumbs = [
            'Businesss' => route('ubayda.business.admin.index'),
            'Businesss' => route('ubayda.business.admin.index'),
        ];


    }

    /**
     * ====================================================================================================================================
     * =========================================== BORDER OF USER ACCESSED BUSINESS =======================================================
     * ====================================================================================================================================
     */
    /**
     * =============================================
     *      list of all business owned by me
     * =============================================
     */
    public function indexUserBusiness(Request $request)
    {

        $userId = Auth::user()->id;
        $isOwnerOnly = false;

        $businesses = $this->businessService->listAllBusiness(null, null, null, null, $userId, $isOwnerOnly);

        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['List' => null]);

        $alerts = AlertHelper::getAlerts();

         //check and or set the session
         $activeBusiness = Session::get('MY_ACTIVE_BUSINESS', null);
         Log::debug("isinya activeBusiness ".$activeBusiness);

        return view('admin.ubayda.business.user.index', compact('businesses', 'breadcrumbs', 'alerts', 'activeBusiness'));
    }

    /**
     * =============================================
     *      list of all business owned by me
     * =============================================
     */
    public function selectUserBusiness(Request $request){
        $businessId = $request->id;


        // dd($data);
        if (!is_null($businessId) && $this->businessService->isExists($businessId)) {
            $business = $this->businessService->getBusinessDetail($businessId);
            //set session
            Session::put('MY_ACTIVE_BUSINESS', $businessId);
            Session::put('MY_ACTIVE_BUSINESS_NAME', $business->name);

            //save the log
            $this->businessService->selectUserBusiness($businessId, Auth::user()->id);


            $alert = AlertHelper::createAlert('success', 'Success select Business with Name : ' . $business->name);

        } else {
            $alert = AlertHelper::createAlert('danger', 'Error : Cannot Select Business, Oops! no such data with that ID : ' . $request->id);

        }


        return redirect()->route('ubayda.business.user.index')->with('alerts', [$alert]);
    }
}
