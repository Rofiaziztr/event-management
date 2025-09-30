<?php

namespace App\Http\Controllers;

use App\Helpers\Alert;
use Illuminate\Http\Request;

class AlertExampleController extends Controller
{
    public function showExamples()
    {
        return view('alert-examples');
    }
    
    public function successAlert()
    {
        Alert::success('Operasi berhasil dilakukan!', [
            'title' => 'Berhasil',
            'duration' => 5000
        ]);
        
        return redirect()->back();
    }
    
    public function errorAlert()
    {
        Alert::error('Maaf, terjadi kesalahan saat memproses permintaan Anda.', [
            'title' => 'Error',
            'duration' => 7000
        ]);
        
        return redirect()->back();
    }
    
    public function warningAlert()
    {
        Alert::warning('Harap perhatikan bahwa beberapa fitur mungkin tidak tersedia saat ini.', [
            'title' => 'Perhatian',
            'duration' => 8000
        ]);
        
        return redirect()->back();
    }
    
    public function infoAlert()
    {
        Alert::info('Sistem akan melakukan pemeliharaan pada tanggal 30 Mei 2023.', [
            'title' => 'Informasi',
            'duration' => 10000
        ]);
        
        return redirect()->back();
    }
    
    public function jsAlert(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan via AJAX',
            'data' => $request->all()
        ]);
    }
}