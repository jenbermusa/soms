<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function index(Request $request) {
        // Correct way to assign default values for sorting
        $sortColumn = $request->get('sortColumn', 'updated_at'); // default to 'updated_at'
        $sortOrder = $request->get('sortOrder', 'desc'); // default to 'desc'
    
        $users = User::with('roles', 'permissions')
            ->orderBy($sortColumn, $sortOrder) // Apply ordering based on parameters
            ->get();
            
        return Inertia::render('User/Index', [
            'User' => $users, // Pass to Vue component
        ]);
    }
    

    public function edit(User $user) {

        if ($user->hasAnyRole(['ADMIN', 'SUPER-ADMIN'])) {
            return abort(404);
        }
        
        return Inertia::render('User/Edit', [
            'user' => $user,
        ]);
        
    }

    public function destroy(User $user) {
        $user->delete();
        return Redirect::back()->with('message', 'User Deleted Successfully');
    }

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|string|exists:roles,name', // Validate role exists
        ]);
    
        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Assign the role
        $user->assignRole($request->input('role'));
    
        return Redirect::route('user.index')->with('message', 'User registered successfully.');
    }
    

    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'required|string|min:8', // Password is optional
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if ($request->filled('password')) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return redirect()->route('user.index')->with('message', 'User updated successfully.');
}
public function toggleStatus(User $user)
{
    $user->status = $user->status === 'Active' ? 'Deactivated' : 'Active';
    $user->save();

    return redirect()->route('user.index')->with('flash', [
        'message' => 'User status updated successfully!',
    ]);
}

}

