<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(\App\Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllPaginated(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(\App\Http\Requests\Admin\UserStoreRequest $request)
    {
        $validated = $request->validated();
        $this->userService->createUser($validated);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userService->findUserById($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(\App\Http\Requests\Admin\UserUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->userService->updateUser($id, $validated);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }
        
        $this->userService->deleteUser($id);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
