<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
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
        return $room;
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
     * Use email to send verify code
     *
     * @param email $email
     */
    public function forgotPassword($email)
    {
        $user = $this->userRepository->findByEmail($email);
        $profile = $user->profile;
        $code = Str::random(config('const.VERIFICATION.CODE_LENGTH'));
        $this->userRepository->updateVerification($user->id, [
            'user_id' => $user->id,
            'code' => $code,
            'expire_at' => Carbon::now()->addMinutes(config('const.VERIFICATION.EXPIRE'))
        ]);
        $user->full_name = "$profile->lastname $profile->firstname";
        $this->mailServices->forgotPassword($user, $code);
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
        $user = $this->userRepository->update($id, ['password' => Hash::make($params['password'])]);
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
