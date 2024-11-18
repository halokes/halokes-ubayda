<?php

namespace App\Services\Ubayda;

use App\Models\Ubayda\Business;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Ubayda\BusinessRepository;

class BusinessService
{
    private $businessRepository;

    /**
     * =============================================
     *  constructor
     * =============================================
     */
    public function __construct(BusinessRepository $businessRepository)
    {
        $this->businessRepository = $businessRepository;
    }

    /**
     * =============================================
     *  list all business along with filter, sort, etc
     * =============================================
     */
    public function listAllBusiness($perPage = null, string $sortField = null, string $sortOrder = null, string $keyword = null): LengthAwarePaginator
    {
        $perPage = !is_null($perPage) ? $perPage : config('constant.CRUD.PER_PAGE');

        return $this->businessRepository->getAllBusiness($perPage, $sortField, $sortOrder, $keyword);
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
    public function addNewBusiness(array $validatedData)
    {
        DB::beginTransaction();
        try {
            $business = $this->businessRepository->createBusiness($validatedData);
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
    public function updateBusiness(array $validatedData, $id)
    {
        DB::beginTransaction();
        try {

            $updatedBusiness = $this->businessRepository->updateBusiness($id, $validatedData);

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
