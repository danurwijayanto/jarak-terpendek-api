<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Model\PlaceCode;

class PlaceDetails extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'place_detail';

    protected $primaryKey = 'pd_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The roles that belong to the details.
    */
    public function details()
    {
        return $this->belongsToMany('App\Model\PlaceCode', 'code_detail', 'pd_id', 'pc_id');
    }

    /**
     * The roles that belong to the details.
    */
    public function details_destination()
    {
        return $this->belongsToMany('App\Model\PlaceCode', 'code_detail', 'pd_id_destination', 'pc_id');
    }
}
