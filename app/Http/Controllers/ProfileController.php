<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Display the profile page
    public function show()
    {
        $user = Auth::user(); // Retrieve the logged-in user's information
        return view('profile', compact('user')); // Pass data to the view
    }

    // Update user profile information
    public function update(Request $request)
    {
        $user = Auth::user(); // Definisikan $user sebelum digunakan

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nik' => 'required|digits:16|unique:users,nik,' . $user->id, // Perbaikan validasi nik
            'phone_number' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->input('name');
        $user->nik = $request->input('nik');        
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->jabatan = $request->input('jabatan');
        $user->address = $request->input('address');

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        $user->update($request->only(['name', 'email', 'nik', 'phone_number', 'jabatan', 'address']));

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }

    // Change user password
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->back()->with('status', 'Password updated successfully.');
    }

    // Upload user signature
    public function uploadSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png|max:2048',
        ]);

        $user = Auth::user();

        // Delete the old signature if it exists
        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
        }

        // Save the new signature file
        $path = $request->file('signature')->store('signatures', 'public');
        $user->signature_path = $path;
        $user->save();

        return redirect()->back()->with('status', 'Signature uploaded successfully.');
    }
    
}
