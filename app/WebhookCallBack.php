<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookCallBack extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['webhook_id','scheduled_waiting_time','status','message'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function webhook()
    {
        return $this->belongsTo(Webhook::class);
    }
}
