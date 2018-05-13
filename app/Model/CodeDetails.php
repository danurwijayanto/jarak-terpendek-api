<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CodeDetails extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'code_detail';

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

    public function details()
    {
        return $this->belongsTo('App\Model\PlaceDetails', 'pd_id', 'pd_id');
    }

    public function details_code()
    {
        return $this->belongsTo('App\Model\PlaceCode', 'pc_id', 'pc_id');
    }
}
