<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ===========
     * RELATIONSHIPS
     * ===========
     */
    public function favourite_coins()
    {
        return CoinUser::where('user_id', $this->id)->pluck('coin_id')->all();
    }

    /**
     * ===========
     * HELPERS
     * ===========
     */
    /**
     * Favourite/unfavourite coin
     * @param int $coin_id Coin ID user favourite/unfavourite
     */
    public function toggleFavouriteCoin($coin_id)
    {
        $favourited = $this->favouriteCoin($coin_id);
        if(!$favourited) {
            CoinUser::create([
                'user_id' => $this->id,
                'coin_id' => $coin_id,
            ]);
        } else {
            CoinUser::where('user_id', $this->id)->where('coin_id', $coin_id)->delete();
        }
    }

    /**
     * Returns true if user has favourited coin, else return false
     * @return boolean
     */
    public function favouriteCoin($coin_id)
    {
        return CoinUser::where('user_id', $this->id)->where('coin_id', $coin_id)->exists();
    }

    /**
     * Returns all favourite coin IDs by user
     * @return Collection
     */
    public function favouriteCoinIds()
    {
        $favourite_coins = CoinUser::where('user_id', $this->id)->get();
        return $favourite_coins->pluck('coin_id')->all();
    }
}
