<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['event_name','callback_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
