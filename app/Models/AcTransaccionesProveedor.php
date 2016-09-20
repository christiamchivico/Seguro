<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcTransaccionesProveedor extends Model {

    /**
     * Generated
     */

    protected $table = 'ac_transacciones_proveedor';
    protected $fillable = ['id', 'codigo_proveedor', 'status', 'deleted_at'];



}
