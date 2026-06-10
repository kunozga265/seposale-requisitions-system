<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    public function transportOptions()
    {
        return $this->hasMany(TransportOption::class);
    }

    function getGeographicCenter()
    {
        $coordinates = json_decode($this->coordinates, true);
        $numCoords = count($coordinates);
        if ($numCoords === 0) return null;

        $X = 0.0;
        $Y = 0.0;
        $Z = 0.0;

        foreach ($coordinates as $coord) {
            $lat = deg2rad($coord['lat']);
            $lon = deg2rad($coord['lng']);

            // Convert to Cartesian (3D) coordinates
            $X += cos($lat) * cos($lon);
            $Y += cos($lat) * sin($lon);
            $Z += sin($lat);
        }

        // Average the Cartesian coordinates
        $X /= $numCoords;
        $Y /= $numCoords;
        $Z /= $numCoords;

        // Convert back to Latitude and Longitude
        $lon = atan2($Y, $X);
        $hyp = sqrt($X * $X + $Y * $Y);
        $lat = atan2($Z, $hyp);

        return [
            'name' => $this->name,
            'lat' => rad2deg($lat),
            'lng' => rad2deg($lon)
        ];
    }

    public function deliverySummary()
    {
        return Summary::query()
            ->selectRaw('
        products.id,
        products.name,
        SUM(deliveries.quantity_delivered) as quantity,
        COUNT(DISTINCT delivery_notes.id) as deliveries
    ')
            ->join('sales', 'sales.id', '=', 'summaries.sale_id')
            ->join('products', 'products.id', '=', 'summaries.product_id')
            ->join('deliveries', 'deliveries.summary_id', '=', 'summaries.id')
            ->leftJoin('delivery_notes', 'delivery_notes.delivery_id', '=', 'deliveries.id')
            ->where('sales.zone_id', $this->id)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quantity')
            ->get();
    }

    protected $fillable = [
        'name',
        'cost',
        'level',
        'coordinates',
        'costs',
    ];
}
