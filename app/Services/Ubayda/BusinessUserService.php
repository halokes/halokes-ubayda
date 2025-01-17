<?php

namespace App\Services\Ubayda;

use App\Models\Ubayda\Business;
use App\Models\Ubayda\BusinessUser;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Ubayda\BusinessRepository;
use App\Repositories\Ubayda\BusinessUserRepository;
use Carbon\Carbon;

class BusinessUserService
{
    private $businessRepository;
    private $businessUserRepository;

    /**
     * =============================================
     *  constructor
     * =============================================
     */
    public function __construct(BusinessRepository $businessRepository, BusinessUserRepository $businessUserRepository)
    {
        $this->businessRepository = $businessRepository;
        $this->businessUserRepository = $businessUserRepository;
    }

    /**
     * =============================================
     *  list all business along with filter, sort, etc
     * =============================================
     */
    public function listAllBusiness($perPage = null, string $sortField = null, string $sortOrder = null, string $keyword = null, string $userId = null, bool $isOwner = false): LengthAwarePaginator
    {
        $perPage = !is_null($perPage) ? $perPage : config('constant.CRUD.PER_PAGE');

        return $this->businessRepository->getAllBusiness($perPage, $sortField, $sortOrder, $keyword,  $userId, $isOwner);
    }

    /**
     * =============================================
     * get single business data
     * =============================================
     */
    public function getBusinessDetail($businessId): ?Business
    {
        return $this->businessRepository->getBusinessById($businessId);
    }




    /**
     * =============================================
     * process add new business to database
     * =============================================
     */
    public function addNewBusinessUser(array $validatedData, $userId)
    {
        DB::beginTransaction();
        try {
            //add into business table
            $business = $this->businessRepository->createBusiness($validatedData);

            //add ownership
            $ownershipdata = [
                'user_id'   => $userId,
                'business_id'   => $business->id,
                'role'      => config('ubayda.UBAYDA_BUSINESS_OWNER'),
                'last_selected' => Carbon::now()
            ];
            $this->businessUserRepository->createBusinessUser($ownershipdata);

            DB::commit();
            return $business;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Failed to save business data to database: {$exception->getMessage()}");
            return null;
        }
    }

    /**
     * =============================================
     * process update business data
     * =============================================
     */
    public function updateBusiness($businessId, array $validatedData, )
    {
        DB::beginTransaction();
        try {

            $updatedBusiness = $this->businessRepository->updateBusiness($businessId, $validatedData);

            DB::commit();
            return $updatedBusiness;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Failed to update business in the database: {$exception->getMessage()}");
            return null;
        }
    }

/**
     * =============================================
     * process SELECT BUSINESS
     * =============================================
     */
    public function selectBusinessUser($businessId, $userId, $role=null) : ?bool{

        DB::beginTransaction();
        try {

            $updatedBusiness = $this->businessUserRepository->getBusinessUserByBusinessAndUser($businessId, $userId, $role)[0];
            $this->businessUserRepository->updateBusinessUser($updatedBusiness->id, ["last_selected" => Carbon::now()]);

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Failed to update data on last select business in the database: {$exception->getMessage()}");
            return false;
        }
    }

    public function findLastSelectedBusiness($userId) : ?Business{

        DB::beginTransaction();
        try {
            Log::debug("find last selected business untuk user Id : ".$userId);
            $businessUser =  $this->businessUserRepository->findLatestSelectedBusiness($userId);
            if(!is_null($businessUser)){
                return $this->businessRepository->findOrFail($businessUser->business_id);
            }
            else{
                return null;
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Failed to fetch business data : {$exception->getMessage()}");
            return null;
        }
    }


     /**
     * =============================================
     * process CHECK IF A business EXISTS
     * =============================================
     */
    public function isExists($businessId): ?bool{
        return $this->businessRepository->exists($businessId);
    }


    /**
     * =============================================
     * process CHECK IF A business can be deleted
     * =============================================
     */
    public function isDeleteable($businessId): ?bool{

        // PUT YOUR LOGIC ABOUT A DATA CAN BE DELETED OR NOT HERE

        return true;
    }

    /**
     * =============================================
     * process delete business
     * =============================================
     */
    public function deleteBusiness($businessId): ?bool
    {
        DB::beginTransaction();
        try {
            if(!$this->isDeleteable($businessId)){
                throw("This data cannot be deleted");
            }

            $this->businessRepository->deleteBusinessById($businessId);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Failed to delete business with id $businessId: {$exception->getMessage()}");
            return false;
        }
    }
}
