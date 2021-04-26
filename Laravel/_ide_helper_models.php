<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Streamer
 *
 * @property int $id
 * @property string $streamer
 * @property int $run
 * @property int $is_online
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereStreamer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Streamer whereUpdatedAt($value)
 */
	class Streamer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\subs
 *
 * @property int $id
 * @property string $gifttype
 * @property string|null $gifter
 * @property string|null $recipient
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $streamer
 * @property string $plan
 * @method static \Illuminate\Database\Eloquent\Builder|subs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|subs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|subs query()
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereGifter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereGifttype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereStreamer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|subs whereUpdatedAt($value)
 */
	class subs extends \Eloquent {}
}

