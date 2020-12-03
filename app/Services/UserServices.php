<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserServices
{
    private const FOLDER = "avatar";
    protected $userRepository;
    protected $imageServices;
    protected $mailServices;

    public function __construct(
        UserRepository $userRepository,
        ImageServices $imageServices,
        MailServices $mailServices
    ) {
        $this->userRepository = $userRepository;
        $this->imageServices = $imageServices;
        $this->mailServices = $mailServices;
    }

    public function index($paginate = 10)
    {
        return $this->userRepository->list($paginate);
    }

    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Find user by user id
     *
     * @param integer $id
     *
     * @return \App\Models\User
     */
    public function findById($id)
    {
        return $this->userRepository->findById($id);
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
        $id = is_null($id) ? $authUser->id : $id;
        if (!$this->permissible($id)) {
            $with = ['profile' => function ($query) {
                $query->select(['user_id', 'firstname', 'lastname', 'phone', 'date_of_birth']);
            }];
            $select = ['id', 'email', 'name', 'room_id'];
        } else {
            $with = ['profile', 'setting', 'room', 'application'];
            $select = "*";
        }
        return $this->userRepository->first(['id' => $id], $with, $select);
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
        return $this->userRepository->update(['id' => $id], ['verify' => $verifyStatus]);
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
        if (array_key_exists('profile', $params)) {
            $profile = $params['profile'];
            $this->userRepository->updateProfile($id, $profile);
            unset($params['profile']);
        }
        if (array_key_exists('setting', $params)) {
            $setting = $params['setting'];
            $this->userRepository->updateSetting($id, $setting);
            unset($params['setting']);
        }
        return $this->userRepository->update(['id' => $id], $params);
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
     * Get user's rented room
     *
     * @return \App\Models\Room
     */
    public function room()
    {
        $authUser = Auth::user();
        $room = $authUser->room;
        if (is_null($room)) {
            return abort(404, 'Không thể tìm thấy phòng đã thuê');
        }
        $room->house;
        $room->payments;
        $room->roommateWanted;
        return $room;
    }

    public function sendVerify($email)
    {
        $user = $this->userRepository->first(['email' => $email], ['profile']);
        $code = Str::random(config('const.VERIFICATION.CODE_LENGTH'));
        $this->userRepository->updateVerification($user->id, [
            'user_id' => $user->id,
            'code' => $code,
            'expire_at' => Carbon::now()->addMinutes(config('const.VERIFICATION.EXPIRE'))
        ]);
        $user->full_name = is_null($user->profile->firstname)&&is_null($user->profile->lastname)
                            ?"$user->profile->lastname $user->profile->firstname"
                            :$user->name;
        $this->mailServices->verifyEmail($user, $code);
        return $user;
    }

    /**
     * Verify verification code
     *
     * @param integer $id
     * @param string $code
     *
     * @return string
     */
    public function verifyCode($id, $code)
    {
        $user = $this->userRepository->findById($id);
        $verification = $user->verification;
        $now = Carbon::now();
        if ($now > $verification->expire_at) {
            return abort(400, 'Mã xác thực hết hạn');
        }
        if ($code!==$verification->code) {
            return abort(400, 'Mã xác thực không chính xác');
        }
        $token = Str::random(config('const.VERIFICATION.TOKEN_LENGTH'));
        $this->userRepository->updateVerification($id, [
            'code' => null,
            'token' => $token
        ]);
        return $token;
    }

    /**
     * Update user password
     *
     * @param integer $id
     * @param string $password
     *
     * @return \App\Models\User
     */
    public function updatePassword($id, $params)
    {
        if (!array_key_exists('token', $params)) {
            return abort(403, 'You do not have permission');
        }
        $this->verifyToken($id, $params['token']);
        $user = $this->userRepository->update(
            ['id' => $id],
            ['password' => Hash::make($params['password'])]
        );
        return $user;
    }

    /**
     * Verify verification token
     *
     * @param integer $id
     * @param string $token
     *
     * @return bool
     */
    public function verifyToken($id, $token)
    {
        $user = $this->userRepository->findById($id);
        $verification = $user->verification;
        $now = Carbon::now();
        if ($now > $verification->expire_at) {
            return abort(400, 'Mã xác thực hết hạn');
        }
        if ($token!==$verification->token) {
            return abort(400, 'Mã xác thực không chính xác');
        }
        $this->userRepository->updateVerification($id, [
            'expire_at' => null,
            'token' => null
        ]);
        return true;
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
        if (!Auth::check()) {
            return false;
        }
        $authUser = Auth::user();
        return $authUser->role==config('const.USER.ROLE.ADMIN')||$authUser->id==$id;
    }
}
