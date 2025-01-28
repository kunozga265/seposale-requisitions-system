<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function siteSale()
    {
        return $this->belongsTo(SiteSale::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function listOfProducts()
    {
        $list = "";

        $products = json_decode($this->information);
        for ($i= 0; $i < count($products); $i++){
           if($i < (count($products) - 1)){
               $list.= $products[$i]->name.", ";
           }else{
               $list.= $products[$i]->name;
           }
        }

        return $list;

    }

    public function information()
    {
        $summaries = [];
        if($this->information != null) {
            $information = json_decode($this->information);

            foreach ($information as $info) {
                if($this->sale != null){
                    $summary = Summary::find($info->id);
                    $product_id = $summary->product->id;
                    $product_name = $summary->product->name;
                }elseif ($this->siteSale !=null){
                    $summary = SiteSaleSummary::find($info->id);
                    $product_id = $summary->inventory->id;
                    $product_name = $summary->inventory->name;
                }

                $summaries[] = [
                    "id" => $info->id,
                    "name" => $info->name,
                    "balance" => $info->balance,
                    "amount" => $info->amount,
                    "product_id" => $product_id,
                    "product_name" => $product_name,
                ];
            }

            return $summaries;
        }else{
            return null;
        }
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    protected $fillable=[
        "serial",
        "client_id",
        "sale_id",
        "site_sale_id",
        "payment_method_id",
        "user_id",
        "amount",
        "code",
        "information",
        "reference",
        "date",
        "whatsapp",
    ];
}
