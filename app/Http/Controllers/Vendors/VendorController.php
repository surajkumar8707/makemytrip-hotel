<?php

namespace App\Http\Controllers\Vendors;

use App\DataTables\ContactsDataTable;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\SocialMediaLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class VendorController extends Controller
{
    public function dashboard()
    {
        $vendor = vendorDetails();
        // dd($vendor);
        return view('vendors.dashboard', compact('vendor'));
    }

    public function dashboardData()
    {
        $today_total_present_student = Attendance::whereDate('date', date('Y-m-d'))->where('present', 1)->pluck('student_id')->count();

        return array(
            'today_total_present_student' => $today_total_present_student,
        );
    }

    public function cacheClear()
    {
        try {
            // Clear configuration cache
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            // Clear application cache
            Artisan::call('cache:clear');

            // Clear route cache
            Artisan::call('route:clear');

            // Clear view cache
            Artisan::call('view:clear');

            // Optimize the application
            Artisan::call('optimize:clear');
            session::put('success', 'All caches cleared successfully!');
            return redirect()->back()->with('success', 'All caches cleared successfully!');
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Cache clear error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to clear caches: ' . $e->getMessage());
        }
    }


    public function profile()
    {
        $vendor = vendorDetails();
        // dd($vendor);
        return view('vendors.profile', compact('vendor'));
    }

    public function profileUpdate(Request $request)
    {
        $vendor = vendorDetails();

        $vendorValidate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'country' => 'nullable',
            'state' => 'nullable',
            'city' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        try {
            // dd($request->all(), $vendorValidate);
            $vendor->update($vendorValidate);
            return redirect()->back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function changePassword()
    {
        $vendor = Auth::guard('vendor')->user();
        // dd($vendor);
        return view('vendors.profile', compact('vendor'));
    }

    public function updatePassword(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        // Validate the request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $currentPassword = $request->current_password;

        // Check if the current password matches the one in the database
        if (!Hash::check($currentPassword, $vendor->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        try {
            // Update the password
            $vendor->password = $request->password;
            $vendor->save();

            return redirect()->back()->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }


    public function setting()
    {
        $setting = Setting::first();
        return view('vendors.setting', compact('setting'));
    }

    public function settingUpdate(Request $request, $id)
    {
        $settings = Setting::find($id);
        // dd($settings->toArray());
        // Validate incoming request
        $request->validate([
            'app_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:20',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for header image
        ]);
        // updateOrCreate
        try {
            // Retrieve all input data from the form
            $input = $request->except(['_token', 'header_image']);

            // Handle file upload for header image if provided
            if ($request->hasFile('header_image')) {

                if ($settings->is_fresh == 0) {
                    deletePublicPathFiles(public_path($settings->header_image));
                }
                $image = $request->file('header_image');
                $imageName = $image->hashName(); // Generate a hashed name for the file
                $image->move(public_path('assets/front/images/header'), $imageName); // Move the uploaded file to public directory

                $input['header_image'] = 'assets/front/images/header/' . $imageName;
                $input['is_fresh'] = 0;
            }

            // Use updateOrCreate to update existing record or create a new one
            Setting::updateOrCreate(
                ['id' => 1], // Assuming there's only one record in the settings table
                $input
            );

            return redirect()->route('admin.setting')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function contacts(ContactsDataTable $dataTable)
    {
        try {
            // $contacts = Contact::paginate();
            // return view('admin.contacts', compact('contacts'));
            return $dataTable->render('admin.contacts');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function bookings()
    {
        $bookings = Booking::with('room')->orderBy('created_at', 'DESC')->get(); // Assuming each booking has a 'room' relationship
        return view('admin.bookings.index', compact('bookings'));
    }

    public function socialMedia()
    {
        try {
            $social_media = SocialMediaLink::first();
            return view('admin.social_media', compact('social_media'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function socialMediaUpdate(Request $request, $id)
    {
        try {
            // Validate incoming request
            $request->validate([
                'youTube' => 'nullable|url',
                'instagram' => 'nullable|url',
                'facebook' => 'nullable|url',
                'linkedin' => 'nullable|url',
            ]);

            $input = $request->only(['youTube', 'instagram', 'facebook', 'linkedin']);
            $input['youTube_show'] = (isset($request->youTube_show)) ? true : false;
            $input['instagram_show'] = (isset($request->instagram_show)) ? true : false;
            $input['facebook_show'] = (isset($request->facebook_show)) ? true : false;
            $input['linkedin_show'] = (isset($request->linkedin_show)) ? true : false;
            $input['twitter_show'] = (isset($request->twitter_show)) ? true : false;
            // dd($input, $request->all());

            // Find the social media record by ID or create a new one
            $social_media = SocialMediaLink::updateOrCreate(
                ['id' => $id],
                $input
            );

            return redirect()->route('admin.social.media')->with('success', 'Social media links updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function aboutPage() {}
}
