<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['event_id','callback_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function webhookcallback()
    {
        return $this->hasOne(WebhookCallBack::class);
    }

    /**
     * creating a new webhook callback
     * @param $message
     */
    public function addWebhook($message)
    {
        $this->webhookcallback()->create([
            "message" => $message
        ]);
    }
}
