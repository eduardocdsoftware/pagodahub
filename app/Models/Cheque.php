<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'fecha',
        'id_factura',
        'pago_presupuesto',
        'monto',
    ];    
}
