<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\ReviewRoom;
use App\Models\ReviewHouse;
use App\Models\ReviewOwner;
use App\Models\ReviewRenter;
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
     * Find first review with conditions
     *
     * @param array $where
     *
     * @return \App\Models\Review
     */
    public function first(array $where)
    {
        return $this->review->where($where)->firstOrFail();
    }

    /**
     * List and paginate reviews
     *
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
        $select = "reviews.*,
        if(reviews.anonymous,null,(select name from users where id=reviews.user_id)) as user_name,
        if(reviews.anonymous,null,(select image from profiles where profiles.user_id=reviews.user_id)) as user_avatar";
        $reviews = $this->review->selectRaw($select)
            ->join('review_renters', 'reviews.id', '=', 'review_renters.review_id');
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
        $select = "reviews.*,
        if(reviews.anonymous,null,(select name from users where id=reviews.user_id)) as user_name,
        if(reviews.anonymous,null,(select image from profiles where profiles.user_id=reviews.user_id)) as user_avatar";
        $reviews = $this->review->selectRaw($select)
            ->join('review_houses', 'reviews.id', '=', 'review_houses.review_id');
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
        return $this->reviewRoom->where('room_id', $roomId)
            ->with(['review' => function ($query) {
                $query->select("*")->selectRaw("IF(
                    reviews.anonymous,
                    null,
                    (SELECT name FROM users WHERE id=reviews.user_id)
                ) AS user_name, IF(
                    reviews.anonymous,
                    null,
                    (SELECT image FROM profiles WHERE profiles.user_id=reviews.user_id)
                ) AS user_avatar");
            }])->paginate($paginate);
    }

    /**
     * Store review
     *
     * @param integer $type
     * @param array $params
     *
     * @return \App\Models\Review
     */
    public function store(array $params)
    {
        return $this->review->create($params);
    }

    /**
     * Create ReviewRoom
     *
     * @param array $params
     *
     * @return \App\Models\ReviewRoom
     */
    public function storeReviewRoom(array $params)
    {
        return $this->reviewRoom->create($params);
    }

    public function storeReviewRenter(array $params)
    {
        return $this->reviewRenter->create($params);
    }

    /**
     * Create ReviewOwner
     *
     * @param array $params
     *
     * @return \App\Models\ReviewOwner
     */
    public function storeReviewOwner($params)
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
    public function updateRoomRate($room, $rate)
    {
        $rate_count = $room->rate_count + 1;
        $avg_rate = ($room->rate_count * $room->avg_rate + $rate) / ($rate_count);
        return DB::transaction(function () use ($room, $avg_rate, $rate_count) {
            $room->update(['avg_rate' => $avg_rate, 'rate_count' => $rate_count]);
            return $room;
        });
    }

    /**
     * Update user
     *
     * @param \App\Models\User $user
     * @param integer $rate
     *
     * @return \App\Models\User
     */
    public function updateUserRate($user, $rate)
    {
        $rate_count = $user->rate_count + 1;
        $avg_rate = ($user->rate_count * $user->avg_rate + $rate) / $rate_count;
        return DB::transaction(function () use ($user, $avg_rate, $rate_count) {
            $user->update(['avg_rate' => $avg_rate, 'rate_count' => $rate_count]);
            return $user;
        });
    }

    /**
     * Update review record
     *
     * @param array $where
     * @param array $params
     *
     * @return \App\Models\Review
     */
    public function update(array $where, array $params)
    {
        $review = $this->review->where($where)->firstOrFail();
        return DB::transaction(function () use ($review, $params) {
            $review->update($params);
            return $review;
        });
    }

    /**
     * Delete review
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->review->where('id', $id)->delete();
    }
}
