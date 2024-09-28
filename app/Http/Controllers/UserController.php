<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to fetch users at the moment.');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        try {
            return view('users.create');
        } catch (\Exception $e) {
            Log::error('Error showing user creation form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the user creation form.');
        }
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'isAdmin' => 'boolean',
            ]);

            // Create a new user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'isAdmin' => $request->isAdmin ?? false,
            ]);

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the user.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        try {
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error displaying user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to display the user.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        try {
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error showing user edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the user edit form.');
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'isAdmin' => 'boolean',
            ]);

            // Update the user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'isAdmin' => $request->isAdmin ?? $user->isAdmin,
            ]);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the user.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the user.');
        }
    }
}
