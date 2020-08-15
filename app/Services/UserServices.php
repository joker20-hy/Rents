<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserServices
{
    private const FOLDER = "avatar";
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($paginate = 10)
    {
        return $this->userRepository->list($paginate);
    }

    /**
     * @param array $params
     */
    public function store(array $params)
    {
        $params["password"] = Hash::make($params["password"]);
        $user = $this->userRepository->store($params);
        return $user;
    }

    public function show($id = null)
    {
        $authUser = Auth::user();
        if (is_null($id)) {
            $user = $authUser;
        } elseif (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        } else {
            $user = $this->userRepository->findById($id);
        }
        $user->profile;
        return $user;
    }

    /**
     * Update verify status of user
     *
     * @param integer $id
     * @param integer $verifyStatus
     *
     * @return \App\Models\User
     */
    public function updateVerifyStatus($id, $verifyStatus)
    {
        return $this->userRepository->update($id, ['verify' => $verifyStatus]);
    }

    /**
     * Get user profile
     *
     * @param integer $id
     *
     * @return \App\Models\Profile
     */
    public function showProfile($id)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        return $this->userRepository->showProfile($id);
    }

    public function updateProfile($id, $params)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        return $this->userRepository->updateProfile($id, $params);
    }

    public function updateSetting($id, $params)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        return $this->userRepository->updateSetting($id, $params);
    }

    /**
     * Update profile image
     *
     * @param file $image
     *
     * @return string
     */
    public function updateAvatar($id, $image)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        $url = $this->imageServices->store([$image], self::FOLDER)[0];
        $this->userRepository->updateProfile($id, ['image' => $url]);
        return $url;
    }

    public function destroy($id)
    {
        $this->userRepository->destroy($id);
    }

    /**
     * Rent room
     *
     * @param integer $id
     * @param integer $roomId
     *
     * @return \App\Models\User
     */
    public function rentRoom($id, $roomId)
    {
        if (!$this->permissible($id)) {
            return abort(403, "Bạn không có quyền thực hiện hành động này");
        }
        return $this->userRepository->update($id, [
            'role' => config('const.USER.ROLE.RENTER'),
            'room_id' => $roomId
        ]);
    }

    /**
     * Leave room
     *
     * @param integer $id
     *
     * @return \App\Models\User
     */
    public function leaveRoom($id)
    {
        return $this->userRepository->update($id, [
            'role' => config('const.USER.ROLE.NORMAL'),
            'room_id' => null
        ]);
    }

    /**
     * Check if user has permission
     *
     * @param integer $id
     *
     * @return boolean
     */
    private function permissible($id)
    {
        $authUser = Auth::user();
        return $authUser->role==config('const.USER.ROLE.ADMIN')||$authUser->id==$id;
    }
}
