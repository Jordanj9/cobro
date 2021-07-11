<?php

namespace Src\services\permisos;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\DescuentoCreate;
use App\ProductoEmbalaje;
use Illuminate\Support\Facades\Auth;

class DocumentDescuentoStore implements DocumentManager
{
    public function generar($request,$code): void
    {
        $user = Auth::user();
        $producto_embalaje = ProductoEmbalaje::findOrFail($request->producto_embalaje_id);
        $request->producto = $producto_embalaje->producto->nombre.'x'.$producto_embalaje->producto->presentacion;
        $request->embalaje = $producto_embalaje->embalaje->descripcion.'x'.$producto_embalaje->unidades;
        $pdf = PDF::loadView('pdfs.descuentoStore',['request' => $request])
                  ->setPaper('a4', 'landscape')
                  ->output();
        Mail::to(config('admin.email_admin','ing.beto.rojas@gmail.com'))->send(new DescuentoCreate($pdf, $code,$user));
    }
}
