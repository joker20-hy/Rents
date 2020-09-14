<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserServices
{
    private const FOLDER = "avatar";
    protected $userRepository;
    protected $imageServices;

    public function __construct(UserRepository $userRepository, ImageServices $imageServices)
    {
        $this->userRepository = $userRepository;
        $this->imageServices = $imageServices;
    }

    public function index($paginate = 10)
    {
        return $this->userRepository->list($paginate);
    }

    /**
     * Create user record
     *
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function store(array $params)
    {
        $params["password"] = Hash::make($params["password"]);
        $user = $this->userRepository->store($params);
        return $user;
    }

    /**
     * Show user info
     *
     * @param integer|null $id
     *
     * @return \App\Models\User
     */
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
        $user->setting;
        $user->room;
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

    /**
     * Update use profile
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function updateProfile($id, array $params)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        return $this->userRepository->updateProfile($id, $params);
    }

    /**
     * Update use profile
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\User
     */
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

    /**
     * Update user info
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\User
     */
    public function update($id, array $params)
    {
        if (!$this->permissible($id)) {
            return abort(403, 'Bạn khồng có quyền thực hiện hành động này');
        }
        $profile = $params['profile'];
        $this->userRepository->updateProfile($id, $profile);
        unset($params['profile']);
        $setting = $params['setting'];
        $this->userRepository->updateSetting($id, $setting);
        unset($params['setting']);
        return $this->userRepository->update($id, $params);
    }

    /**
     * Delete user
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->userRepository->destroy($id);
    }

    /**
     * Rent room
     *
     * @param integer $roomId
     * @param integer|null $id
     *
     * @return \App\Models\User
     */
    public function rentRoom($roomId, $id = null)
    {
        $id = is_null($id) ? Auth::user()->id : $id;
        $user = $this->userRepository->findById($id);
        if ($roomId==$user->room_id) {
            return abort(400, 'You are already in this room');
        }
        return $this->userRepository->rentRoom($id, $roomId);
    }

    /**
     * Leave room
     *
     * @param integer $id
     *
     * @return \App\Models\User
     */
    public function leaveRoom($id = null)
    {
        $id = is_null($id) ? Auth::user()->id : $id;
        return $this->userRepository->leaveRoom($id);
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
