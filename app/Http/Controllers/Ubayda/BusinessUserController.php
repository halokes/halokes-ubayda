<?php

namespace App\Http\Controllers\Ubayda;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubayda\BusinessUserAddRequest;
use App\Http\Requests\Ubayda\BusinessUserEditRequest;
use App\Models\Ubayda\Business;
use App\Services\Ubayda\BusinessService;
use App\Services\Ubayda\BusinessUserService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BusinessUserController extends Controller
{
    private $businessService;
    private $businessUserService;
    private $mainBreadcrumbs;
    private $activeBusiness;

    public function __construct(BusinessService $businessService, BusinessUserService $businessUserService)
    {
        $this->businessService = $businessService;
        $this->businessUserService = $businessUserService;

        // Store common breadcrumbs in the constructor
        $this->mainBreadcrumbs = [
            'My Businesss' => route('ubayda.business.user.index'),
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
    public function indexBusinessUser(Request $request)
    {

        $userId = Auth::user()->id;
        $isOwnerOnly = false;

        $businesses = $this->businessService->listAllBusiness(null, null, null, null, $userId, $isOwnerOnly);

        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['List' => null]);

        $alerts = AlertHelper::getAlerts();

        //check and or set the session
        $activeBusiness = Session::get('MY_ACTIVE_BUSINESS', null);
        // Log::debug("isinya activeBusiness " . $activeBusiness);

        return view('admin.ubayda.businessuser.index', compact('businesses', 'breadcrumbs', 'alerts', 'activeBusiness'));
    }

    /**
     * =============================================
     *      list of all business owned by me
     * =============================================
     */
    public function selectBusinessUser(Request $request)
    {
        $businessId = $request->id;


        // dd($data);
        if (!is_null($businessId) && $this->businessService->isExists($businessId)) {
            $business = $this->businessService->getBusinessDetail($businessId);
            //set session
            Session::put('MY_ACTIVE_BUSINESS', $businessId);
            Session::put('MY_ACTIVE_BUSINESS_NAME', $business->name);

            //save the log
            $this->businessUserService->selectBusinessUser($businessId, Auth::user()->id);


            $alert = AlertHelper::createAlert('success', 'Success select Business with Name : ' . $business->name);
        } else {
            $alert = AlertHelper::createAlert('danger', 'Error : Cannot Select Business, Oops! no such data with that ID : ' . $request->id);
        }


        return redirect()->route('ubayda.business.user.index')->with('alerts', [$alert]);
    }

    /**
     * =============================================
     *      ADD NEW BUSINESS - display Form
     * =============================================
     */
    public function createBusinessUser(Request $request)
    {
        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Add' => null]);
        $listType = config('ubayda.BUSINESS_TYPE');

        return view('admin.ubayda.businessuser.add', compact('breadcrumbs', 'listType'));
    }

    /**
     * =============================================
     *      ADD NEW BUSINESS - store data
     * =============================================
     */
    public function storeBusinessUser(BusinessUserAddRequest $request)
    {
        $validatedData = $request->validated();

        $result = $this->businessUserService->addNewBusinessUser($validatedData, $request->user()->id);

        $alert = $result
            ? AlertHelper::createAlert('success', 'Data ' . $result->name . ' successfully added')
            : AlertHelper::createAlert('danger', 'Data ' . $request->name . ' failed to be added');



        return redirect()->route('ubayda.business.user.index')->with([
            'alerts'        => [$alert]
        ]);
    }

    /**
     * =============================================
     *      EDIT THE BUSINESS - show form
     * =============================================
     */
    public function editBusinessUser(Business $business)
    {
        // dd($business);
        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Edit' => null]);
        $listType = config('ubayda.BUSINESS_TYPE');

        return view('admin.ubayda.businessuser.edit', compact('breadcrumbs', 'listType', 'business'));
    }


    /**
     * =============================================
     *      EDIT THE BUSINESS - show form
     * =============================================
     */
    public function updateBusinessUser(Business $business, BusinessUserEditRequest $request)
    {

        // dd($business->id);
        //to do here
        $validatedData = $request->validated();

        $result = $this->businessUserService->updateBusiness($business->id, $validatedData);

        $alert = $result
            ? AlertHelper::createAlert('success', 'Data business ' . $result->name . ' successfully edited')
            : AlertHelper::createAlert('danger', 'Data business ' . $request->name . ' failed to be edited');


        return redirect()->route('ubayda.business.user.index')->with([
            'alerts'        => [$alert]
        ]);
    }
}
