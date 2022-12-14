<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessCreateRequest;
use App\Http\Requests\BusinessUpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Business;
use App\Models\Category;
use App\Models\Coordinates;
use App\Models\Location;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessesController extends Controller
{
    use ResponseTrait;
//
//    /**
//     * Display a listing of the resource.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  string  $location ;
//     * @return \Illuminate\Http\Response
//     */
//    public function getData(Request $request) //getting JSON Data from external API and saving it to DB
//    {
//        try {
//            $client = new \GuzzleHttp\Client();
//            $query = $request->getQueryString();
//            $APIurl = 'https://api.yelp.com/v3/businesses/search';
//            $urlRequest = $APIurl.'?'.$query;
//
//            $response = $client->request('GET', $urlRequest,
//                [
//                    'headers' => [
//                        'Authorization' => 'Bearer B_vCVsupon8KuDuRv0R1suyTZp0xfFv8PEIgfgZhB5XrjnFUYOyL-pcrwQGwyc3ED3ULKCcyHvYZgexyzr9Gu6yfLyJqSEZ5TLBGjCRHHlBIWzg3vtiB6Xu0O6WVY3Yx',
//                        'accept' => 'application/json',
//                    ],
//                ]);
//            $dataArray = json_decode($response->getBody()->getContents(), true);
//            foreach ($dataArray['businesses'] as $businesses) { //formatting & inserting JSON object to DB
//                $Businesses = new Business;
//                $Businesses->key = $businesses['id'];
//                $Businesses->alias = $businesses['alias'];
//                $Businesses->name = $businesses['name'];
//                $Businesses->image_url = $businesses['image_url'];
//                $Businesses->is_closed = $businesses['is_closed'];
//                $Businesses->url = $businesses['url'];
//                $Businesses->review_count = $businesses['review_count'];
//                $Businesses->rating = $businesses['rating'];
//                $output = '';
//                $size = (count((array) $businesses['transactions']));
//                foreach ($businesses['transactions'] as $row) {
//                    $output = $output.$row;
//                    if ($size > 1) {
//                        $output = $output.',';
//                        $size--;
//                    }
//                }
//                $Businesses->transactions = $output;
//                $Businesses->price = $businesses['price'] ?? null;
//                $Businesses->phone = $businesses['phone'];
//                $Businesses->display_phone = $businesses['display_phone'];
//                $Businesses->distance = $businesses['distance'];
//                $Businesses->save();
//
//                foreach ($businesses['categories'] as $businessesCategories) {
//                    $Category = new Category;
//                    $Category->business = $Businesses->id;
//                    $Category->alias = $businessesCategories['alias'];
//                    $Category->title = $businessesCategories['title'];
//                    $Category->save();
//                }
//
//                $Coordinates = new Coordinates;
//                $Coordinates->business = $Businesses->id;
//                $Coordinates->latitude = $businesses['coordinates']['latitude'];
//                $Coordinates->longitude = $businesses['coordinates']['longitude'];
//                $Coordinates->save();
//
//                $Location = new Location;
//                $Location->business = $Businesses->id;
//                $Location->address1 = $businesses['location']['address1'];
//                $Location->address2 = $businesses['location']['address2'];
//                $Location->address3 = $businesses['location']['address3'];
//                $Location->city = $businesses['location']['city'];
//                $Location->zip_code = $businesses['location']['zip_code'];
//                $Location->country = $businesses['location']['country'];
//                $Location->state = $businesses['location']['state'];
//                $output = '';
//                $size = (count((array) $businesses['location']['display_address']));
//                foreach ($businesses['location']['display_address'] as $row) {
//                    $output = $output.$row;
//                    if ($size > 1) {
//                        $output = $output.',';
//                        $size--;
//                    }
//                }
//                $Location->display_address = $output;
//                $Location->save();
//            }
//        } catch (Exception $e) {
//            Log::error($e->getMessage());
//        }
//    }

    public function search(Request $request): JsonResponse //search data with query.
    {
        try
        {
            $data = Business::with('categories')
                ->when($request->has('is_closed'), static function ($query) use ($request) //search by is_closed true/false
                {
                    if (!blank($request->alias))
                    {
                        return $query->where('is_closed', 'LIKE', "%{$request->is_closed}%");
                    }
                    return $query;
                })
                ->when($request->has('alias'), static function ($query) use ($request) //search by category-alias
                {
                    if (!blank($request->alias))
                    {
                        return $query->where('alias', 'LIKE', "%{$request->alias}%");
                    }
                    return $query;
                })
                ->when($request->has('price'), static function ($query) use ($request) //search by price
                {
                    if (!blank($request->price))
                    {
                        return $query->where('price', 'LIKE', "%{$request->price}%");
                    }
                    return $query;
                })
                ->when($request->has('offset'), static function ($query) use ($request) //pagination offset
                {
                    if (!blank($request->offset))
                    {
                        return $query->offset($request->offset);
                    }
                    return $query;
                })
                ->when($request->has('limit'), static function ($query) use ($request) //pagination limit
                {
                    if (!blank($request->limit))
                    {
                        return $query->limit($request->limit);
                    }
                    return $query;
                })->get();

            return $this->sendResponse($data);
        }
        catch (Exception $e)
        {
            return $this->logAndSendErrorResponse($e->getMessage(), $e);
        }
    }

    /**
     * @param  BusinessCreateRequest  $request
     * @return JsonResponse
     */
    public function store(BusinessCreateRequest $request): JsonResponse //insert values to DB
    {
        try
        {
            DB::beginTransaction();

            // get category from request
            $categories = collect($request->categories);

            // get category record from DB by its alias
            $catRecords = Category::whereIn('alias', $categories->pluck('alias'))
                ->get();
            $notInRecords = $categories->whereNotIn('alias', $catRecords->pluck('alias'));

            // create categories if not exists
            foreach ($notInRecords as $notInRecord)
            {
                $catRecords[] = Category::create($notInRecord);
            }

            $business = Business::create($request->except('categories'));

            // sync with categories
            $business->categories()->sync($catRecords->pluck('id'));

            DB::commit();

            return $this->sendResponse($business);
        }
        catch (Exception $e)
        {
            DB::rollBack();

            return $this->logAndSendErrorResponse($e->getMessage(), $e);
        }
    }

    /**
     * @param  BusinessUpdateRequest  $request
     * @return JsonResponse
     */
    public function update(BusinessUpdateRequest $request): JsonResponse //updating existing data by id
    {
        try
        {
            DB::beginTransaction();

            $business = Business::findOrFail($request->id);
            $business->update($request->all());

            DB::commit();

            return $this->sendResponse($business);
        }
        catch (Exception $e)
        {
            DB::rollBack();

            return $this->logAndSendErrorResponse($e->getMessage(), $e);
        }
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse //deleting a data entry by id
    {
        try
        {
            DB::beginTransaction();

            $business = Business::findOrFail($request->id);
            $business->delete();

            DB::commit();

            return $this->sendResponse
            ([
                'message' => 'Business Deleted',
            ]);
        }
        catch (Exception $e)
        {
            DB::rollBack();

            return $this->logAndSendErrorResponse($e->getMessage(), $e);
        }
    }
}
