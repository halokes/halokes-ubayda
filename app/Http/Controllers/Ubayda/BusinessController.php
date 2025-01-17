<?php

namespace App\Http\Controllers\Ubayda;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubayda\BusinessFindUserRequest;
use App\Http\Requests\Ubayda\BusinessListRequest;
use App\Services\Ubayda\BusinessService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{

    private $businessService;
    private $mainBreadcrumbs;
    private $userService;

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

    // ============================ START OF ULTIMATE CRUD FUNCTIONALITY ===============================



    /**
     * =============================================
     *      list all search and filter/sort things
     * =============================================
     */
    public function indexAdmin(BusinessListRequest $request)
    {
        $sortField = session()->get('sort_field', $request->input('sort_field', 'id'));
        $sortOrder = session()->get('sort_order', $request->input('sort_order', 'asc'));

        $perPage = $request->input('per_page', config('constant.CRUD.PER_PAGE'));
        $page = $request->input('page', config('constant.CRUD.PAGE'));
        $keyword = $request->input('keyword');

        $userId = $request->input('userId');

        $businesses = $this->businessService->listAllBusiness($perPage, $sortField, $sortOrder, $keyword, $userId);

        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['List' => null]);

        $alerts = AlertHelper::getAlerts();

        return view('admin.ubayda.business.admin.index', compact('businesses', 'breadcrumbs', 'sortField', 'sortOrder', 'perPage', 'page', 'keyword', 'alerts'));
    }

    /**
     * =============================================
     *      see the detail of single Subscription
     * =============================================
     */
    public function detailAdmin(Request $request)
    {
        $data = $this->businessService->getBusinessDetail($request->id);
        $alerts = AlertHelper::getAlerts();

        // dd($data);
        if ($data) {
            $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Detail' => null]);

            return view('admin.ubayda.business.admin.detail', compact('breadcrumbs', 'data', 'alerts'));
        } else {
            $alert = AlertHelper::createAlert('danger', 'Error : Cannot View Business Detail, Oops! no such data with that ID : ' . $request->id);

            return redirect()->route('ubayda.business.admin.index')->with('alerts', [$alert]);
        }
    }

    /**
     * =============================================
     *      process create Business
     * =============================================
     */
    public function create(BusinessFindUserRequest $request)
    {

        $sortField = session()->get('sort_field', $request->input('sort_field', 'id'));
        $sortOrder = session()->get('sort_order', $request->input('sort_order', 'asc'));

        $perPage = $request->input('per_page', config('constant.CRUD.PER_PAGE'));
        $page = $request->input('page', config('constant.CRUD.PAGE'));
        $keyword = $request->input('keyword');
        $alerts = AlertHelper::getAlerts();

        // JIKA USER SUDAH DISET / BELUM
        if (isset($request->user)) {
            $userIsSet = true;
            $findUsers = null;
            //find users first
            $userFound = $this->userService->getUserDetail($request->user);

            // if user is not found or invalid users
            if (!$userFound) {
                $alert =  AlertHelper::createAlert('danger', 'Error : Invalid user Id : ' . $request->user);
                $userFound = null;
                return redirect()->route('ubayda.business.admin.index')->with([
                    'alerts' => [$alert]
                ]);
            }

            if (!$userFound->is_active) {
                $alerts = [...$alerts, AlertHelper::createAlert('danger', 'This user is inactive state, you cannot add business to this')];
            }

            $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Add (Step 2/2 : Add Business)' => null]);


            return view('admin.ubayda.business.admin.add', compact('userIsSet', 'alerts', 'userFound', 'breadcrumbs'));
        } else {
            $userFound = null;

            $userIsSet = false;
            $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Add (Step 1/2 : Choose User)' => route('ubayda.business.admin.add')]);

            $findUsers = $this->userService->listAllUser($perPage, $sortField, $sortOrder, $keyword);

            $alerts = AlertHelper::getAlerts();

            return view('admin.ubayda.business.admin.find-user', compact('userIsSet', 'userFound', 'findUsers', 'breadcrumbs', 'sortField', 'sortOrder', 'perPage', 'page', 'keyword'));
        }
    }



    /**
     * =============================================
     *      suspend and unsuspend
     * =============================================
     */
    public function suspendAdmin($businessId, $suspendAction = 1)
    {
        $action = $suspendAction == 1 ? "suspend" : "unsuspend";
        $subscriptionData = $this->subscriptionUserService->getSubscriptionDetail($subscriptionId);
        $userEmail =  $subscriptionData->user->email;
        $userPackage =  $subscriptionData->package->package_name;
        try {
            $data = $this->subscriptionUserService->suspendUnsuspend($subscriptionId, $suspendAction, Auth::user()->id);
            if (!$data) {
                throw new Exception("failed to " . $action . " data, returned data is null / false from repository ");
            }
            $alert = AlertHelper::createAlert('success', 'Success ' . $action . ' data with ID : ' . $subscriptionId . " (" . $userEmail . " - " . $userPackage . ")");
        } catch (Exception $e) {
            Log::error("Error suspend / unsuspend caused by ", [
                "subscriptionId"    => $subscriptionId,
                "cause" => $e->getMessage()
            ]);
            $alert = AlertHelper::createAlert('danger', 'Error : Failed to ' . $action . ' data with ID : ' . $subscriptionId . " (" . $userEmail . " - " . $userPackage . ")");
        }

        return redirect()->back()->with([
            'alerts' => [$alert],
            'sort_field' => 'subscription_user.updated_at',
            'sort_order' => 'desc'
        ]);
    }

    public function unsuspendAdmin($businessId)
    {
        return $this->suspend($businessId, 2);
    }

    /**
     * =============================================
     *      proses "add new business" from previous form
     * =============================================
     */
    public function store(BusinessAddRequest $request)
    {
        $validatedData = $request->validated();

        // dd($validatedData);

        $result = $this->businessService->addNewBusiness($validatedData);

        $alert = $result
            ? AlertHelper::createAlert('success', 'Data ' . $result->business_name . ' successfully added')
            : AlertHelper::createAlert('danger', 'Data ' . $result->business_name . ' failed to be added');

        return redirect()->route('subscription.business.index')->with([
            'alerts'        => [$alert],
            'sort_order'    => 'desc'
        ]);
    }

    // /**
    //  * =============================================
    //  *      see the detail of single business entity
    //  * =============================================
    //  */
    // public function detail(Request $request)
    // {
    //     $data = $this->businessService->getBusinessDetail($request->id);

    //     // dd($data);
    //     if($data){
    //         $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Detail' => null]);

    //         return view('admin.ubayda.businessdetail', compact('breadcrumbs', 'data'));
    //     }
    //     else{
    //         $alert = AlertHelper::createAlert('danger', 'Error : Cannot View Detail, Oops! no such data with that ID : ' . $request->id);

    //         return redirect()->route('subscription.business.index')->with('alerts', [$alert]);
    //     }


    // }

    // /**
    //  * =============================================
    //  *     display "edit business" pages
    //  * =============================================
    //  */
    // public function edit(Request $request, $id)
    // {
    //     $business = $this->businessService->getBusinessDetail($id);

    //     if ($business) {
    //         $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Edit' => null]);

    //         return view('admin.ubayda.businessedit', compact('breadcrumbs', 'business'));
    //     } else {
    //         $alert = AlertHelper::createAlert('danger', 'Error : Cannot edit, Oops! no such data with that ID : ' . $request->id);

    //         return redirect()->route('subscription.business.index')->with('alerts', [$alert]);
    //     }
    // }

    // /**
    //  * =============================================
    //  *      process "edit business" from previous form
    //  * =============================================
    //  */
    // public function update(BusinessEditRequest $request, $id)
    // {
    //     $result = $this->businessService->updateBusiness($request->validated(), $id);


    //     $alert = $result
    //         ? AlertHelper::createAlert('success', 'Business ' . $result->alias . ' successfully updated')
    //         : AlertHelper::createAlert('danger', 'Business ' . $request->alias . ' failed to be updated');

    //     return redirect()->route('subscription.business.index')->with([
    //         'alerts' => [$alert],
    //         'sort_field' => 'updated_at',
    //         'sort_order' => 'desc'
    //     ]);
    // }

    // /**
    //  * =============================================
    //  *    show delete confirmation for business
    //  *    while showing the details to make sure
    //  *    it is correct data which they want to delete
    //  * =============================================
    //  */
    // public function deleteConfirm(BusinessListRequest $request)
    // {
    //     $isDeleteable = $this->businessService->isDeleteable($request->id);
    //     $data = $this->businessService->getBusinessDetail($request->id);
    //     if ($data) {
    //         $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Delete' => null]);
    //         return view('admin.ubayda.businessdelete-confirm', compact('breadcrumbs', 'data', 'isDeleteable'));
    //     } else {
    //         $alert = AlertHelper::createAlert('danger', 'Error : Cannot delete, Oops! no such data with that ID : ' . $request->id);

    //         return redirect()->route('subscription.business.index')->with('alerts', [$alert]);
    //     }
    // }

    // /**
    //  * =============================================
    //  *      process delete data
    //  * =============================================
    //  */
    // public function destroy(BusinessListRequest $request)
    // {
    //     $business = $this->businessService->getBusinessDetail($request->id);

    //     if (!is_null($business)) {
    //         $result = $this->businessService->deleteBusiness($request->id);
    //     } else {
    //         $result = false;
    //     }

    //     $alert = $result
    //         ? AlertHelper::createAlert('success', 'Data ' . $business->alias . ' successfully deleted')
    //         : AlertHelper::createAlert('danger', 'Oops! failed to be deleted');

    //     return redirect()->route('subscription.business.index')->with('alerts', [$alert]);
    // }




}
