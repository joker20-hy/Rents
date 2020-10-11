<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\ReviewRenter;
use App\Models\ReviewHouse;
use App\Models\ReviewOwner;
use App\Models\ReviewRoom;
use App\Models\Room;

class ReviewRepository
{
    protected $review;
    protected $reviewRenter;
    protected $reviewHouse;
    protected $reviewOwner;
    protected $reviewRoom;
    protected $room;

    public function __construct(
        Room $room,
        Review $review,
        ReviewRoom $reviewRoom,
        ReviewHouse $reviewHouse,
        ReviewOwner $reviewOwner,
        ReviewRenter $reviewRenter
    ) {
        $this->review = $review;
        $this->reviewRenter = $reviewRenter;
        $this->reviewHouse = $reviewHouse;
        $this->reviewOwner = $reviewOwner;
        $this->reviewRoom = $reviewRoom;
        $this->room = $room;
    }

    /**
     * @param integer $paginate
     */
    public function listAll($paginate = 10)
    {
        return $this->review->paginate($paginate);
    }

    /**
     * List review of owner
     *
     * @param integer $ownerId
     * @param integer $paginate
     *
     * @return mixed
     */
    public function listOwner($ownerId = null, $paginate = 10)
    {
        $reviews = $this->review->selectRaw("
            reviews.*,
            if (reviews.anonymous, null, (select name from users where id=reviews.user_id)) as user_name,
            if (reviews.anonymous, null, (select image from profiles where profiles.user_id=reviews.user_id)) as user_avatar
        ")->join('review_owners', 'reviews.id', '=', 'review_owners.review_id');
        $reviews = $reviews->where('review_owners.owner_id', $ownerId);
        return $reviews->paginate($paginate);
    }

    /**
     * List review of renter
     *
     * @param integer $renterId
     * @param integer $paginate
     *
     * @return mixed
     */
    public function listRenter($renterId = null, $paginate = 10)
    {
        $reviews = $this->review->selectRaw("
            reviews.*,
            if (
                reviews.anonymous,
                null,
                (select name from users where id=reviews.user_id)
            ) as user_name,
            if (
                reviews.anonymous,
                null,
                (select image from profiles where profiles.user_id=reviews.user_id)
            ) as user_avatar
        ")->join('review_renters', 'reviews.id', '=', 'review_renters.review_id');
        $reviews = $reviews->where('review_renters.renter_id', $renterId);
        return $reviews->paginate($paginate);
        
    }

    /**
     * List reviews of houses
     *
     * @param integer $houseId
     * @param integer $paginate
     *
     * @return mixed
     */
    public function listHouse($houseId = null, $paginate = 10)
    {
        $reviews = $this->review->selectRaw("
            reviews.*,
            if (
                reviews.anonymous,
                null,
                (select name from users where id=reviews.user_id)
            ) as user_name,
            if (
                reviews.anonymous,
                null,
                (select image from profiles where profiles.user_id=reviews.user_id)
            ) as user_avatar
        ")->join('review_houses', 'reviews.id', '=', 'review_houses.review_id');
        $reviews = $reviews->where('review_houses.house_id', $houseId);
        return $reviews->paginate($paginate);
    }

    /**
     * List reviews of rooms
     *
     * @param integer $roomId
     * @param integer $paginate
     *
     * @return mixed
     */
    public function listRoom($roomId = null, $paginate = 10)
    {
        $reviews = $this->review->selectRaw("
            reviews.*,
            if (
                reviews.anonymous,
                null,
                (select name from users where id=reviews.user_id)
            ) as user_name,
            if (
                reviews.anonymous,
                null,
                (select image from profiles where profiles.user_id=reviews.user_id)
            ) as user_avatar
        ")->join('review_rooms', 'reviews.id', '=', 'review_rooms.review_id');
        $reviews = $reviews->where('review_rooms.room_id', $roomId);
        return $reviews->with('reviewRooms')->paginate($paginate);
    }

    /**
     * Store review
     *
     * @param integer $type
     * @param array $params
     *
     * @return \App\Models\Review
     */
    public function store($type, $params)
    {
        $review = DB::transaction(function () use ($type, $params) {
            $review = $this->review->create($params);
            switch ($type) {
                case config('const.REVIEW.TYPE.ROOM'):
                    $room = $this->room->findOrFail($params['criteria_id']);
                    $this->storeByRoom($review, $room, [
                        'infra_rate' => $params['infra_rate'],
                        'secure_rate' => $params['secure_rate'],
                        'owner_rate' => $params['owner_rate']
                    ]);
                    break;
                case config('const.REVIEW.TYPE.RENTER'):
                    # code...
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
        return $review;
    }

    /**
     * Store related info to review
     *
     * @param \App\Models\Review $review
     * @param \App\Models\Room $room
     * @param array $rates
     */
    private function storeByRoom($review, $room, $rates)
    {
        $reviewRoom = $this->storeReviewRoom([
            'review_id' => $review->id,
            'room_id' => $room->id,
            'secure_rate' => $rates['secure_rate'],
            'infra_rate' => $rates['infra_rate']
        ]);
        $owner = $this->findOwnersByRoom($room)->owner;
        $this->storeReviewOwner([
            'review_id' => $review->id,
            'owner_id' => $owner->id,
            'rate' => $rates['owner_rate']
        ]);
        $this->updateOwnerRate($owner, $rates['owner_rate']);;
        $roomRate = ($reviewRoom->secure_rate + $reviewRoom->infra_rate) / 2;
        $this->updateRoomRate($room, $roomRate);
    }

    /**
     * Create ReviewRoom
     *
     * @param array $params
     *
     * @return \App\Models\ReviewRoom
     */
    private function storeReviewRoom($params)
    {
        $reviewRoom = DB::transaction(function () use ($params) {
            return $this->reviewRoom->create($params);
        });
        return $reviewRoom;
    }

    /**
     * Create ReviewOwner
     *
     * @param array $params
     *
     * @return \App\Models\ReviewOwner
     */
    private function storeReviewOwner($params)
    {
        $reviewOwner = DB::transaction(function () use ($params) {
            return $this->reviewOwner->create($params);
        });
        return $reviewOwner;
    }

    /**
     * Update room rate
     *
     * @param \App\Models\Room $room
     * @param integer $rate
     *
     * @return \App\Models\Room
     */
    private function updateRoomRate($room, $rate)
    {
        $rate_count = $room->rate_count + 1;
        $avg_rate = ($room->rate_count * $room->avg_rate + $rate) / ($rate_count);
        $room = DB::transaction(function () use ($room, $avg_rate, $rate_count) {
            $room->update([
                'avg_rate' => $avg_rate,
                'rate_count' => $rate_count
            ]);
            return $room;
        });
        return $room;
    }

    /**
     * Update owner
     *
     * @param \App\Models\User $owner
     * @param integer $rate
     *
     * @return \App\Models\User
     */
    private function updateOwnerRate($owner, $rate)
    {
        $rate_count = $owner->rate_count + 1;
        $avg_rate = ($owner->rate_count * $owner->avg_rate + $rate) / ($rate_count);
        $owner = DB::transaction(function () use ($owner, $avg_rate, $rate_count) {
            $owner->update(['avg_rate' => $avg_rate, 'rate_count' => $rate_count]);
            return $owner;
        });
        return $owner;
    }

    /**
     * Find owner and tenants of room
     *
     * @param \App\Models\Room $room
     *
     * @return object
     */
    private function findOwnersByRoom($room)
    {
        $userHouses = $room->house->userHouses;
        $owner = null;
        $tenant = [];
        foreach ($userHouses as $userHouse) {
            if ($userHouse->role==config('const.OWNER_ROLE.OWNER')) {
                $owner = $userHouse->user;
            }
            if ($userHouse->role==config('const.OWNER_ROLE.TENANT')) {
                array_push($tenant, $userHouse->user);
            }
        }
        return (object)[
            'owner' => $owner,
            'tenant' => $tenant
        ];
    }
}
