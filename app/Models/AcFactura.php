<?php namespace APP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcFactura extends Model {

    use SoftDeletes;
    /**
     * Generated
     */

    protected $table = 'ac_facturas';
    protected $fillable = ['id', 'clave', 'numero_factura', 'numero_control', 'fecha_factura', 'monto', 'observaciones', 'fecha_creacion', 'usuario_creador', 'status', 'deleted_at'];
    protected $dates = ['fecha_factura'];
    
    /**
     * The storage format of the model's date columns.
     * @var string
     */
    protected $dateFormat = 'Y-m-d';
    
    function setFechaFacturaAttribute($date) {
        $this->attributes['fecha_factura'] = new Carbon($date);
    }

    public function acClafe() {
        return $this->belongsTo(\APP\Models\AcClave::class, 'clave', 'clave');
    }


}
