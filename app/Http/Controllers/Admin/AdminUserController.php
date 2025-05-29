<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by user type
        if ($request->has('type') && $request->type !== '') {
            if ($request->type === 'admin') {
                $query->where('is_admin', true);
            } else {
                $query->where('is_admin', false);
            }
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_admin'] = $request->has('is_admin');
        $validated['is_active'] = $request->has('is_active');
        
        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(5);
        }]);
        
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->sum('total'),
            'addresses' => $user->addresses()->count(),
            'wishlist_items' => $user->wishlist()->count(),
            'cart_items' => $user->cart ? $user->cart->items()->count() : 0,
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
        
        // Add password validation only if provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }
        
        $validated = $request->validate($rules);

        $validated['is_admin'] = $request->has('is_admin');
        $validated['is_active'] = $request->has('is_active');
        
        // Hash password if provided
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin users
        if ($user->is_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete admin users.');
        }
        
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleActive(User $user)
    {
        // Prevent deactivating admin users
        if ($user->is_admin) {
            return redirect()->back()
                ->with('error', 'Cannot deactivate admin users.');
        }
        
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Cannot deactivate your own account.');
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "User {$status} successfully.");
    }
}