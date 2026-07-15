<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllPaginated(int $perPage = 15, string $search = null)
    {
        return $this->userRepository->getPaginated($perPage, $search);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        $user = $this->userRepository->create($data);
        
        return $user;
    }

    public function updateUser($id, array $data)
    {
        $user = $this->userRepository->findById($id);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        $updatedUser = $this->userRepository->update($id, $data);

        return $updatedUser;
    }

    public function deleteUser($id)
    {
        $user = $this->userRepository->findById($id);
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $email = $user->email;
        $this->userRepository->delete($id);
        
        return true;
    }

    public function findUserById($id)
    {
        return $this->userRepository->findById($id);
    }
}
