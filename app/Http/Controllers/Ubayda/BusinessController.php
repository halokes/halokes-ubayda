<?php

namespace App\Http\Controllers\Ubayda;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ubayda\BusinessListRequest;
use App\Services\Ubayda\BusinessService;
use App\Services\UserService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{

    private $businessService;
    private $mainBreadcrumbs;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;

        // Store common breadcrumbs in the constructor
        $this->mainBreadcrumbs = [
            'Businesss' => route('ubayda.business.index'),
        ];
    }

    // ============================ START OF ULTIMATE CRUD FUNCTIONALITY ===============================



    /**
     * =============================================
     *      list all search and filter/sort things
     * =============================================
     */
    public function index(BusinessListRequest $request)
    {
        $sortField = session()->get('sort_field', $request->input('sort_field', 'id'));
        $sortOrder = session()->get('sort_order', $request->input('sort_order', 'asc'));

        $perPage = $request->input('per_page', config('constant.CRUD.PER_PAGE'));
        $page = $request->input('page', config('constant.CRUD.PAGE'));
        $keyword = $request->input('keyword');

        $businesses = $this->businessService->listAllBusiness($perPage, $sortField, $sortOrder, $keyword);

        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['List' => null]);

        $alerts = AlertHelper::getAlerts();

        return view('admin.ubayda.business.index', compact('businesses', 'breadcrumbs', 'sortField', 'sortOrder', 'perPage', 'page', 'keyword', 'alerts'));
    }

    /**
     * =============================================
     *      see the detail of single Subscription
     * =============================================
     */
    public function detail(Request $request)
    {
        $data = $this->businessService->getBusinessDetail($request->id);
        $alerts = AlertHelper::getAlerts();

        // dd($data);
        if ($data) {
            $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Detail' => null]);

            return view('admin.ubayda.business.detail', compact('breadcrumbs', 'data', 'alerts'));
        } else {
            $alert = AlertHelper::createAlert('danger', 'Error : Cannot View Business Detail, Oops! no such data with that ID : ' . $request->id);

            return redirect()->route('subscription.user.index')->with('alerts', [$alert]);
        }
    }

    /**
     * =============================================
     *      display "add new business" pages
     * =============================================
     */
    public function create(Request $request)
    {

        $breadcrumbs = array_merge($this->mainBreadcrumbs, ['Add' => null]);

        return view('admin.ubayda.businessadd', compact('breadcrumbs'));
    }

    // /**
    //  * =============================================
    //  *      proses "add new business" from previous form
    //  * =============================================
    //  */
    // public function store(BusinessAddRequest $request)
    // {
    //     $validatedData = $request->validated();

    //     // dd($validatedData);

    //     $result = $this->businessService->addNewBusiness($validatedData);

    //     $alert = $result
    //         ? AlertHelper::createAlert('success', 'Data ' . $result->business_name . ' successfully added')
    //         : AlertHelper::createAlert('danger', 'Data ' . $result->business_name . ' failed to be added');

    //     return redirect()->route('subscription.business.index')->with([
    //         'alerts'        => [$alert],
    //         'sort_order'    => 'desc'
    //     ]);
    // }

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
