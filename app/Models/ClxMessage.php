<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\ClxMessage
 *
 * @property int $id
 * @property int $vatsim_account_id
 * @property int $rcl_message_id
 * @property string $flight_level
 * @property string $mach
 * @property int|null $track_id
 * @property string|null $random_routeing
 * @property string $entry_fix
 * @property string|null $entry_time_restriction
 * @property string|null $free_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereEntryFix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereEntryTimeRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereFlightLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereFreeText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereMach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereRandomRouteing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereRclMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereVatsimAccountId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read array $data_link_message
 * @property-read \App\Models\RclMessage $rclMessage
 * @property-read \App\Models\Track|null $track
 * @property-read mixed $simple_message
 * @property string $datalink_authority
 * @method static \Illuminate\Database\Eloquent\Builder|ClxMessage whereDatalinkAuthority($value)
 */
class ClxMessage extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('clx');
    }

    public function rclMessage()
    {
        return $this->belongsTo(RclMessage::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function getDataLinkMessageAttribute()
    {
        $rcl = $this->rclMessage;
        $array = [
            'CLX ' . now()->format('Hi dmy') . ' CZQX CLRNCE ' . $this->id,
            $rcl->callsign . ' CLRD TO ' . $rcl->destination . ' VIA ' . $this->entry_fix,
            $this->track ? 'NAT ' . $this->track->identifier : 'RANDOM ROUTE',
            $this->track ? $this->track->last_routeing : $this->random_routeing,
            'FM ' . $this->entry_fix . '/' . $rcl->entry_time . ' MNTN F' . $this->flight_level . ' M' . $this->mach,
        ];
        if ($this->entry_time_restriction) {
            $array[] = "/ATC CROSS {$this->entry_fix} {$this->entry_time_restriction}";
        }
        if ($this->mach != $rcl->mach) {
            $array[] = "/ATC MACH CHANGED";
        }
        if ($this->flight_level != $rcl->flight_level) {
            $array[] = "/ATC FLIGHT LEVEL CHANGED";
        }
        if ($this->free_text) {
            $array[] = "/ATC " . strtoupper($this->free_text);
        }
        $array[] = "END OF MESSAGE";
        return $array;
    }

    public function getSimpleMessageAttribute()
    {
        $rcl = $this->rclMessage;
        $msg = "";
        if ($this->track) {
            $msg = "Cleared to {$rcl->destination} via {$this->entry_fix}, track {$this->track->identifier}. From {$this->entry_fix} maintain Flight Level {$this->flight_level}, Mach {$this->mach}.";
        } else {
            $msg = "Cleared to {$rcl->destination} via {$this->entry_fix}, random routeing {$this->random_routeing}. From {$this->entry_fix} maintain Flight Level {$this->flight_level}, Mach {$this->mach}.";
        }
        if ($this->entry_time_restriction) {
            $msg .= " Cross {$this->entry_fix} " . strtolower($this->entry_time_restriction) . ".";
        }
        if ($this->mach != $rcl->mach) {
            $msg .= " Mach number changed.";
        }
        if ($this->flight_level != $rcl->flight_level) {
            $msg .= " Flight level changed.";
        }
        if ($this->free_text) {
            $msg .= " {$this->free_text}";
        }
        return $msg;
    }
}
