<?php

namespace App\Repositories\Ubayda;

use App\Models\Ubayda\Business;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BusinessRepository
{
    public function getAllBusiness(int $perPage = 10, string $sortField = null, string $sortOrder = null, String $keyword = null): LengthAwarePaginator
    {
        $queryResult = Business::query();

        // Join the business_user table to get the owner information
        $queryResult->leftJoin('business_user', function ($join) {
            $join->on('business.id', '=', 'business_user.business_id')
                ->where('business_user.role', '=', 'owner');
        })
        ->leftJoin('users', 'business_user.user_id', '=', 'users.id') // Join with the users table
        ->select('business.*', 'users.id as owner_id', 'users.name as owner_name', 'users.email as owner_email'); // Select owner details


        if (!is_null($sortField) && !is_null($sortOrder)) {
            $queryResult->orderBy($sortField, $sortOrder);
        } else {
            $queryResult->orderBy("created_at", "desc");
        }

        if (!is_null($keyword)) {
            $queryResult->whereRaw('lower(business.name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                ->orWhereRaw('lower(users.name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                ->orWhereRaw('lower(users.email) LIKE ?', ['%' . strtolower($keyword) . '%'])
                ->orWhereRaw('lower(address) LIKE ?', ['%' . strtolower($keyword) . '%'])
                ->orWhereRaw('lower(type) LIKE ?', ['%' . strtolower($keyword) . '%']);

            // For uuid columns, use direct comparison
            if (Str::isUuid($keyword)) {
                $queryResult->orWhere('id', $keyword);
            }
        }

        $paginator = $queryResult->paginate($perPage);
        $paginator->withQueryString();

        return $paginator;
    }

    public function findOrFail($id)
    {
        return Business::findOrFail($id);
    }


    public function getBusinessById($id): ?Business
    {
        return Business::find($id);
    }

    public function createBusiness($data)
    {
        return Business::create($data);
    }

    public function updateBusiness($id, $data)
    {
        // Find the data based on the id
        $updatedData = Business::where('id', $id)->first();

        // if data with such id exists
        if ($updatedData) {
            // Update the profile with the provided data
            $updatedData->update($data);
            return $updatedData;
        } else {
            throw new Exception("Business data not found");
        }
    }


    public function deleteBusinessById($id): ?bool
    {
        try {
            $user = Business::findOrFail($id); // Find the data by ID
            $user->delete(); // Delete the data
            return true; // Return true on successful deletion
        } catch (\Exception $e) {
            // Handle any exceptions, such as data not found
            throw new Exception("Business data not found");
        }
    }
}
