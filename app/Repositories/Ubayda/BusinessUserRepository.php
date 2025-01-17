<?php

namespace App\Repositories\Ubayda;

use App\Models\Ubayda\BusinessUser;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BusinessUserRepository
{

    public function findOrFail($id)
    {
        return BusinessUser::findOrFail($id);
    }



    public function exists($id)
    {
        return BusinessUser::where('id', $id)->exists();
    }


    public function getBusinessUserById($id): ?BusinessUser
    {
        return BusinessUser::find($id);
    }

    public function getBusinessUserByBusinessAndUser($businessId, $userId){
        return BusinessUser::where('business_id', $businessId)->where('user_id', $userId)->get();
    }

    public function createBusinessUser($data)
    {
        return BusinessUser::create($data);
    }

    public function updateBusinessUser($id, $data)
    {
        // Find the data based on the id
        $updatedData = BusinessUser::where('id', $id)->first();

        // if BusinessUser data with such id exists
        if ($updatedData) {
            // Update the BusinessUser with the provided data
            $updatedData->update($data);
            return $updatedData;
        } else {
            throw new Exception("BusinessUser data not found");
        }
    }

    public function findLatestSelectedBusiness($userId) : ?BusinessUser {
        return BusinessUser::where('user_id', $userId)->orderBy('last_selected', 'DESC')->orderBy('created_at', 'DESC')->first();
    }


    public function deleteBusinessUserById($id): ?bool
    {
        try {
            $user = BusinessUser::findOrFail($id); // Find the data by ID
            $user->delete(); // Delete the data
            return true; // Return true on successful deletion
        } catch (\Exception $e) {
            // Handle any exceptions, such as data not found
            throw new Exception("BusinessUser data not found");
        }
    }
}
