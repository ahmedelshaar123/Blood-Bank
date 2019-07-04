<?php

namespace App\Http\Controllers\Api;

use App\BloodType;
use App\Category;
use App\City;
use App\DonationRequest;
use App\Governorate;
use App\Post;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MainController extends Controller
{


    public function governorates()
    {
        $governorates = Governorate::all();
        return apiResponse(1, "success", $governorates);
    }

    public function cities(Request $request)
    {
        $cities = City::with('governorate')->where(function ($query) use ($request) {
            if ($request->has('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }
        })->get();

        return apiResponse(1, "success", $cities);
    }


    public function categories()
    {
        $categories = Category::all();
        return apiResponse(1, "success", $categories);
    }

    public function bloodTypes()
    {
        $bloodTypes = BloodType::all();
        return apiResponse(1, "success", $bloodTypes);
    }

    public function settings()
    {
        $settings = Setting::find(1);
        return apiResponse(1, "success", $settings);
    }

    public function showNotifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);
        return apiResponse(1, "success", $notifications);
    }

    public function notificationsCount(Request $request)
    {
        $count = $request->user()->notifications()->where(function ($query) use ($request) {
            $query->where('is_read', 0);
        })->count();
        //$count = $request->user()->notifications()->count();
        return apiResponse(1, "success", [
            'notifications count' => $count,
        ]);
    }


    public function contacts(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);
        if ($validator->fails()) {
            return apiResponse(0, $validator->errors()->first(), $validator->errors());

        }
//        $request->merge(['client_id' => $request->user()->id]);
        //$contact = Contact::create($request->all());
        $contact = $request->user()->contacts()->create($request->all());
        return apiResponse(1, "success", $contact->load('client'));
    }

    public function myFavourites(Request $request)
    {
        $favourites = $request->user()->posts()->with('categories')->latest()->paginate(20);
        return apiResponse(1, "success", $favourites);
    }


    public function posts(Request $request)
    {
        $posts = Post::with('categories')->where(function ($query) use ($request) {
            if ($request->has('category_id')) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                });
            }
            if ($request->has('keyword')) {

                    $query->where(function ($query) use($request){
                        $query->where('title', 'like', '%' . $request->keyword . '%');
                        $query->orWhere('body', 'like', '%' . $request->keyword . '%');
                    });
            }
        })->latest()->paginate(10);
        return apiResponse(1, 'success', $posts);
    }

    public function post(Request $request)
    {

        $post = Post::with('categories')->find($request->post_id);
        if (!$post) {
            return apiResponse(0, '404 no post found');
        }
        return apiResponse(1, 'success', $post);
    }

    public function toggleFavourites(Request $request)
    {

        $rules = [
            'post_id' => 'required|exists:posts,id',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return apiResponse(0, $validator->errors()->first(), $validator->errors());
        }
        $toggle = $request->user()->posts()->toggle($request->post_id);// attach() detach() sync() toggle()
        // [1,2,4] - sync(2,5,7) -> [1,2,4,5,7]
        // detach()
        // attach([2,5,7])
        return apiResponse(1, 'Success', $toggle);
    }

    public function createDonationRequest(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'age' => 'required:digits',
            'blood_type_id' => 'required|exists:blood_types,id',
            'bags_number' => 'required:digits',
            'hospital_name' => 'required',
            'hospital_address' => 'required',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|digits:11',
        ]);

        if ($validator->fails()) {
            return apiResponse(0, $validator->errors()->first(), $validator->errors());
        }

        $donationRequest = $request->user()->donation_requests()->create($request->all())->load('city.governorate', 'blood_type');
        //dd($donationRequest);
        $clientsIds = $donationRequest->city->governorate->clients()
            ->whereHas('blood_types', function ($q) use ($donationRequest) {
                $q->where('blood_types.id', $donationRequest->blood_type_id);
            })->pluck('clients.id')->toArray();



        //dd($clientsIds);
        $send = "";
        if (count($clientsIds)) {
            $notifications = $donationRequest->notifications()->create([
                'title' => 'يوجد حالة تبرع قريبة منك',
                'body' => optional($donationRequest->blood_type)->blood_type . "أحتاج متبرع لفصيلة", // optional??

            ]);
            //dd($notifications);
            $notifications->clients()->attach($clientsIds);
            $tokens = $request->user()->tokens()->where('token', '!=', null)->wherein('client_id', $clientsIds)->pluck('token')->toArray();
            //return $tokens;
            if (count($tokens)) {

                $title = $notifications->title;
                $body = $notifications->body;
                $data = [
                    'donation_request_id' => $donationRequest->id
                ];
                $send = notifyByFirebase($title, $body, $tokens, $data);
                info("firebase result: " . $send);
                //  info("data: " . json_encode($data));
            }

            // dd($send);
        }
        return apiResponse(1, "success", $donationRequest); // no noti??

    }

    public function donationRequest(Request $request)
    {

        $donation = DonationRequest::with('city', 'client', 'blood_type')->find($request->donation_id);
        if (!$donation) {
            return apiResponse(0, '404 no donation found');
        }

        $request->user()->notifications()->updateExistingPivot($donation->notification->id, [
            'is_read' => 1  // why notification ??

        ]);
        return apiResponse(1, "success", $donation);


    }

    public function donationRequests(Request $request){
        $donations = DonationRequest::with('city', 'client', 'blood_type')->where( function($query) use($request){
            if($request->input('governorate_id')){
                $query->whereHas('city', function($query) use($request){
                   $query->where('governorate_id', $request->governorate_id);
                });
            }
            if($request->input('blood_type_id')){
                $query->where('blood_type_id', $request->blood_type_id);
            }
        } )->latest()->paginate(10);
        return apiResponse(1, "success", $donations);
    }
}


















