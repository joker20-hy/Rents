<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ReviewRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;

class ReviewServices
{
    protected $reviewRepository;
    protected $roomRepository;

    public function __construct(
        ReviewRepository $reviewRepository,
        RoomRepository $roomRepository,
        UserRepository $userRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
    }

    public function list($type, $id = null, $paginate = 10)
    {
        switch ($type) {
            case config('const.REVIEW.TYPE.OWNER'):
                return $this->reviewRepository->listOwner($id, $paginate);
                break;
            case config('const.REVIEW.TYPE.RENTER'):
                return $this->reviewRepository->listRenter($id, $paginate);
                break;
            case config('const.REVIEW.TYPE.HOUSE'):
                return $this->reviewRepository->listHouse($id, $paginate);
                break;
            case config('const.REVIEW.TYPE.ROOM'):
                return $this->reviewRepository->listRoom($id, $paginate);
                break;
        }
        return [];
    }

    /**
     * Create review
     *
     * @param array $params
     * @param integer $type
     *
     * @return \App\Models\Review
     */
    public function store(array $params, $type)
    {
        return DB::transaction(function () use ($params, $type) {
            $params['user_id'] = Auth::user()->id;
            $review = $this->reviewRepository->store($params);
            switch ($type) {
                case config('const.REVIEW.TYPE.ROOM'):
                    $room = $this->roomRepository->findById($params['criteria_id']);
                    $this->storeForRoom($review, $room, $params);
                    break;
                case config('const.REVIEW.TYPE.RENTER'):
                    $renter = $this->userRepository->first(['id' => $params['criteria_id']]);
                    $this->storeForRenter($review, $renter, $params['rate']);
                    break;
                case config('const.REVIEW.TYPE.HOUSE'):
                    # code...
                    break;
                case config('const.REVIEW.TYPE.OWNER'):
                    # code...
                    break;
                default:
                    throw "";
                    break;
            }
            return $review;
        });
        
    }

    /**
     * Store room review
     *
     * @param \App\Models\Review $review
     * @param \App\Models\Room $room
     * @param array $rates
     */
    public function storeForRoom($review, $room, array $rates)
    {
        $reviewRoom = $this->reviewRepository->storeReviewRoom([
            'review_id' => $review->id,
            'room_id' => $room->id,
            'secure_rate' => $rates['secure_rate'],
            'infra_rate' => $rates['infra_rate']
        ]);
        $owner = $this->findOwnerByRoom($room);
        $this->reviewRepository->storeReviewOwner([
            'review_id' => $review->id,
            'owner_id' => $owner->id,
            'rate' => $rates['owner_rate']
        ]);
        $this->reviewRepository->updateUserRate($owner, $rates['owner_rate']);
        $roomRate = ($reviewRoom->secure_rate + $reviewRoom->infra_rate) / 2;
        $this->reviewRepository->updateRoomRate($room, $roomRate);
    }

    public function storeForRenter($review, $renter, $rate)
    {
        $reviewRenter = $this->reviewRepository->storeReviewRenter([
            'review_id' => $review->id,
            'renter_id' => $renter->id,
            'rate' => $rate
        ]);
        $this->reviewRepository->updateUserRate($renter, $rate);
    }

    /**
     * Update review
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Review
     */
    public function update($id, array $params)
    {
        $review = $this->reviewRepository->first(['id' => $id]);
        if ($this->permissible($review->user_id)) {
            return $this->reviewRepository->update(['id' => $id], $params);
        } else {
            return abort(403, "You have no permission to actions on the review");
        }
    }

    /**
     * Delete review
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $review = $this->reviewRepository->first(['id' => $id]);
        if ($this->permissible($review->user_id)) {
            $this->reviewRepository->destroy($id);
        } else {
            return abort(403, "You have no permission to actions on the review");
        }
    }

    /**
     * Find owner and tenants of room
     *
     * @param \App\Models\Room $room
     *
     * @return object
     */
    private function findOwnerByRoom($room)
    {
        $userHouse = $room->house->userHouses->first(function ($item) {
            return $item->role==config('const.OWNER_ROLE.OWNER');
        });
        return $userHouse->user;
    }

    /**
     * Check if user has permission
     *
     * @param integer $userId
     *
     * @return boolean
     */
    private function permissible($userId)
    {
        $authUser = Auth::user();
        return $authUser->role==config('USER.ROLE.ADMIN')||$authUser->id==$userId;
    }
}
