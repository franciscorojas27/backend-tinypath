<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortLink extends Model
{
    /** @use HasFactory<\Database\Factories\ShortLinkFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'original_link',
        'short_link',
        'expire_at',
    ];
    protected $appends = [
        'metrics_summary'
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }


    public function getExpireAtAttribute($value)
    {
        return $this->user ? null : $value;
    }

    public function getRouteKey()
    {
        return $this->short_link;
    }

    public function getMetricsSummaryAttribute()
    {
        return [
            'time' => $this->getPeriodCount(),
            'network_clicks' => $this->getClickCountsBy('referrer'),
            'device_clicks' => $this->getClickCountsBy('device'),
            'country_clicks' => $this->getClickCountsBy('country'),
            'city_clicks' => $this->getClickCountsBy('city'),
        ];
    }

    private function getClickCountsBy($column)
    {
        return $this->metrics()
            ->select($column, DB::raw('count(*) as total'))
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->keyBy($column)
            ->map(fn($item) => $item->total);
    }
    private function getPeriodCount()
    {
        $clicksByHour = $this->metrics()
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->get()
            ->keyBy('hour');

        $clicksGroupedByPeriod = [
            'Mañana' => 0,
            'Tarde' => 0,
            'Noche' => 0,
            'Madrugada' => 0,
        ];

        foreach ([
            'Mañana' => [6, 12],
            'Tarde'   => [12, 18],
            'Noche'   => [18, 24],
            'Madrugada' => [0, 6],
        ] as $periodo => [$inicio, $fin]) {
            $clicksGroupedByPeriod[$periodo] = $clicksByHour
                ->filter(fn($click, $hour) => $hour >= $inicio && $hour < $fin)
                ->sum('total');
        }


        return $clicksGroupedByPeriod;
    }
}
