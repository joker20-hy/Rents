<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewRenter;
use App\Models\ReviewHouse;
use App\Models\ReviewOwner;
use App\Models\ReviewRoom;
use App\Models\House;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReviewRepository;

class ReviewServices
{
    protected $review;
    protected $reviewRenter;
    protected $reviewOwner;
    protected $reviewHouse;
    protected $reviewRoom;
    protected $house;
    protected $room;
    protected $reviewRepository;

    public function __construct(
        Review $review,
        ReviewRenter $reviewRenter,
        ReviewHouse $reviewHouse,
        ReviewOwner $reviewOwner,
        ReviewRoom $reviewRoom,
        House $house,
        Room $room,
        ReviewRepository $reviewRepository
    ) {
        $this->review = $review;
        $this->reviewRenter = $reviewRenter;
        $this->reviewHouse = $reviewHouse;
        $this->reviewOwner = $reviewOwner;
        $this->reviewRoom = $reviewRoom;
        $this->house = $house;
        $this->room = $room;
        $this->reviewRepository = $reviewRepository;
    }

    public function list($type, $paginate = 10)
    {
        switch ($type) {
            case config('const.REVIEW.TYPE.OWNER'):
                return $this->reviewRepository->listOwner(null, $paginate);
                break;
            case config('const.REVIEW.TYPE.RENTER'):
                return $this->reviewRepository->listRenter(null, $paginate);
                break;
            case config('const.REVIEW.TYPE.HOUSE'):
                return $this->reviewRepository->listHouse(null, $paginate);
                break;
            case config('const.REVIEW.TYPE.ROOM'):
                return $this->reviewRepository->listRoom(null, $paginate);
                break;
        }
        return [];
    }

    public function index($type, $id, $paginate = 10)
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

    public function renter($paginate)
    {
        $reviews = $this->review->where('receiver_type', config('const.REVIEW.RECEIVER_TYPE.RENTER'))
                                ->with('user')
                                ->paginate($paginate);
        foreach($reviews as $review) {
            $renter = DB::select("select * from users where id=$review->receiver_id");
            $review->renter = $renter; 
        }                                
        return $reviews;
    }

    private function renterFilter($query, $conditions = [])
    {
        return $query;
    }

    public function house($paginate)
    {
        $reviews = $this->review->where('receiver_type', config('const.REVIEW.RECEIVER_TYPE.HOUSE'))
                        ->with('user')
                        ->paginate($paginate);
        foreach ($reviews as $review) {
            $house = DB::select("select * from houses where id=$review->receiver_id");
            $review->house = $house;
        }
        return $reviews;
    }

    private function houseFilter($query, $conditions = [])
    {
        return $query;
    }

    public function room($paginate)
    {
        $reviews = $this->review->where('receiver_type', config('const.REVIEW.RECEIVER_TYPE.ROOM'))
                                ->with('user')
                                ->paginate($paginate);
        foreach ($reviews as $review) {
            $room = DB::select("select * from rooms where id=$review->receiver_id");
            $review->room = $room;
        }
        return $reviews;
    }

    private function roomFilter($query, $conditions = [])
    {
        return $query;
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
        $authUser = Auth::user();
        $params['user_id'] = $authUser->id;
        $review = $this->reviewRepository->store($type, $params);
        return $review;
    }

    private function storeForHouse()
    {
        //
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
        $review = $this->review->findOrFail($id);
        if ($this->permissible($review->user_id)) {
            $review->update($params);
            return $review;
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
        $review = $this->review->findOrFail($id);
        if ($this->permissible($review->user_id)) {
            $review->delete();
        } else {
            return abort(403, "You have no permission to actions on the review");
        }
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
        if ($authUser->role==config('USER.ROLE.ADMIN') || $authUser->id==$userId) {
            return true;
        }
        return false;
    }
}
