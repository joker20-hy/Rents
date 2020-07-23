<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\ReviewRenter;
use App\Models\ReviewHouse;
use App\Models\ReviewOwner;
use App\Models\ReviewRoom;

class ReviewRepository
{
    protected $review;
    protected $reviewRenter;
    protected $reviewHouse;
    protected $reviewOwner;
    protected $reviewRoom;

    public function __construct(
        Review $review,
        ReviewRenter $reviewRenter,
        ReviewHouse $reviewHouse,
        ReviewOwner $reviewOwner,
        ReviewRoom $reviewRoom
    ) {
        $this->review = $review;
        $this->reviewRenter = $reviewRenter;
        $this->reviewHouse = $reviewHouse;
        $this->reviewOwner = $reviewOwner;
        $this->reviewRoom = $reviewRoom;
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
        DB::transaction(function () use ($type, $params) {
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
        $this->updateOwnerRate($owner, $rates['owner_rate']);
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
        return $this->reviewRoom->create($params);
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
        return $this->reviewOwner->create($params);
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
        $room->update([
            'avg_rate' => $avg_rate,
            'rate_count' => $rate_count
        ]);
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
        $owner->update([
            'avg_rate' => $avg_rate,
            'rate_count' => $rate_count
        ]);
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
        $owners = DB::transaction(function () use ($room) {
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
        });
        return $owners;
    }
}
