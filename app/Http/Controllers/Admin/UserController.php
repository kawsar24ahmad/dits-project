<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable',
            'password' => 'required|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'   => 'required|in:active,inactive',
        ]);

        // Handle avatar upload if exists

        if ($request->hasFile('avatar')) {
            // Store new photo
            $filename =  uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('avatars'), $filename);
            $avatar = 'avatars/' . $filename;
        }




        // Create the user
        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->password = bcrypt($request->password);
        $user->avatar   = $avatar ?? null;
        $user->status   = $request->status;
        $user->save();

        // Redirect with success message
        return redirect()->route('admin_users.index')->with('success', 'User created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update user details
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->status = $validated['status'];

        // Only update the password if it's not empty
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Save the user
        $user->save();

        // Redirect back with a success message
        return redirect()->route('admin_users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar && file_exists($user->avatar)) {
            unlink(public_path($user->avatar));
        }
        $user->delete();

        return redirect()->route('admin_users.index')->with('success', 'User deleted successfully.');
    }

}
