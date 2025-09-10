<?php

namespace App\Controllers;

class Berita extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Berita',
            'content' => 'Ini adalah halaman berita. Berita terbaru akan ditampilkan di sini.'
        ];

        return view('template1/view_berita', $data); // Bisa pakai view yang sama untuk demo sederhana
    }
}