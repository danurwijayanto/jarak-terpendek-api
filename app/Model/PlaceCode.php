<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\PlaceDetails;

class PlaceCode extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'place_code';

    protected $primaryKey = 'pc_id';

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
        return $this->belongsToMany('PlaceDetails', 'place_detail', 'pc_id', 'pd_id');
    }
}
