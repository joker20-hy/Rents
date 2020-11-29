<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Services\MailServices;

class OwnerServices
{
	protected $userRepository;
    protected $mailServices;

	public function __construct(UserRepository $userRepository, MailServices $mailServices)
	{
		$this->userRepository = $userRepository;
        $this->mailServices = $mailServices;
	}

	public function listApplication($paginate = 10)
	{
		return $this->userRepository->listApplication($paginate);
	}

	public function createApplication($ownerId, array $profile, $image)
    {
        $user = $this->userRepository->findById($ownerId);
        if ($user->role==config('const.USER.ROLE.OWNER')) {
        	return abort(400, "Bạn đã là chủ nhà");
        } elseif (!is_null($user->application)) {
            return abort(400, "Bạn đã đang đăng ký làm chủ trọ, chúng tôi sẽ phản hồi lại trong thời gian nhanh nhất");
        }
        return DB::transaction(function () use ($ownerId, $profile, $image) {
            $this->userRepository->updateProfile($ownerId, $profile);
            return $this->userRepository->createApplication(['user_id' => $ownerId, 'image' => $image]);
        });
    }

    public function showApplication($id)
    {
        return $this->userRepository->firstApplication(['id' => $id]);
    }

    public function updateApplication($id, array $profile, $image)
    {
        $application = $this->userRepository->firstApplication(['id' => $id]);
        return DB::transaction(function () use ($application, $profile, $image) {
            $this->userRepository->updateProfile($application->user_id, $profile);
            return $this->userRepository->updateApplication(
                ['id' => $application->id],
                ['image' => $image, 'status' => config('const.OWNER_APPLICATION_STATUS.WAITING')]
            );
        });
    }

    public function approveApplication($id)
    {
    	$application = $this->userRepository->firstApplication(['id' => $id]);
    	DB::transaction(function () use ($application) {
    		$this->userRepository->update(['id' => $application->user_id], [
	    		'role' => config('const.USER.ROLE.OWNER'),
	    		'email_verified_at' => Carbon::now()->toDateTimeString(),
	    		'verify' => config('const.USER.VERIFIED')
	    	]);

	    	$this->userRepository->updateApplication(
                ['id' => $application->id],
                ['status' => config('const.OWNER_APPLICATION_STATUS.APPROVED')]
            );
            $this->mailServices->approveOwner($application->user);
    	});
    }

    public function declineApplication($id, $reasons)
    {
        $application = $this->userRepository->firstApplication(['id' => $id]);
        DB::transaction(function () use ($application, $reasons) {
            $this->userRepository->updateApplication(
                ['id' => $application->id],
                ['status' => config('const.OWNER_APPLICATION_STATUS.DECLINED'), 'decline_reasons' => $reasons]
            );
            $this->mailServices->declineOwner($application->user, $reasons);
        });
    }
}
