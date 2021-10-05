<?php


namespace Unbank\CurrencyScraper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Osoobe\Utilities\Traits\TimeDiff;

class CurrencyExchange extends Model {

    use HasFactory;
    use TimeDiff;

    protected $fillable = [
        'from',
        'to',
        'rate',
        'volume',
        'source_data',
        'source',
        'currency_type'
    ];

    protected $casts = [
        'source_data' => 'array'
    ];

    protected $table = "currencies";


    public function scopeExchange($query, $from, $to) {
        return $query->where('from', $from)
            ->where('to', $to);
    }

    public function scopeSource($query, $source) {
        return $query->where('source', $source);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($cex) {
            $cex->formatTicker();
        });

        static::updating(function ($cex) {
            $cex->formatTicker();
        });
    }

    public function formatTicker() {
        $this->from = strtoupper($this->from);
        $this->to = strtoupper($this->to);
    }
}


?>
