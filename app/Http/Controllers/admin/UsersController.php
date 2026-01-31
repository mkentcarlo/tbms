<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if current user can manage the target user
     */
    protected function canManageUser(User $targetUser)
    {
        $currentUser = auth()->user();
        
        // Super admin can manage anyone
        if ($currentUser->hasRole('super_admin')) {
            return true;
        }
        
        // Admin cannot manage super_admin users
        if ($targetUser->hasRole('super_admin')) {
            return false;
        }
        
        return true;
    }

    /**
     * Show the users list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $you = auth()->user();
        
        // If user is super_admin, show all users
        // If user is admin, hide super_admin users
        if ($you->hasRole('super_admin')) {
            $users = User::all();
        } else {
            // Get all users except those with super_admin role
            $users = User::all()->filter(function ($user) {
                return !$user->hasRole('super_admin');
            });
        }
        
        return view('dashboard.admin.usersList', compact('users', 'you'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('dashboard.admin.userCreateForm');
    }

    /**
     * Store a newly created user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign default 'user' role
        $user->assignRole('user');

        return redirect()->route('users.index')->with('message', 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Check if current user can view this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to view this user.');
        }
        
        return view('dashboard.admin.userShow', compact('user'));
    }

    /**
     * Show the form for editing the user.
     * 
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Check if current user can edit this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to edit this user.');
        }
        
        return view('dashboard.admin.userEditForm', compact('user'));
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Check if current user can update this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to update this user.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('users.index')->with('message', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     * 
     * @param int $id 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        
        // Check if current user can delete this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to delete this user.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('message', 'User deleted successfully.');
    }

    /**
     * @deprecated Use destroy() instead
     */
    public function remove($id)
    {
        return $this->destroy($id);
    }

    /**
     * @deprecated Use edit() instead
     */
    public function editForm($id)
    {
        return $this->edit($id);
    }
}
