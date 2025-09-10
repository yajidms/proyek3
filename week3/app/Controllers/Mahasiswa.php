<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use CodeIgniter\HTTP\RedirectResponse;

class Mahasiswa extends BaseController
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        helper(['form', 'url']);
    }

    // List data mahasiswa
    public function index()
    {
        $data = [
            'title' => 'Data Mahasiswa',
            'mahasiswa' => $this->mahasiswaModel->orderBy('NIM', 'ASC')->findAll(),
        ];

        // Menampilkan dalam template sederhana yang juga bisa dipakai sebagai "Home"
        return view('template2/view_mahasiswa', $data);
    }

    // Detail per mahasiswa berdasarkan ID
    public function show($id = null)
    {
        $row = $this->mahasiswaModel->find($id);
        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mahasiswa tidak ditemukan');
        }

        return view('mahasiswa/show', ['title' => 'Detail Mahasiswa', 'row' => $row]);
    }

    // Tampilkan form tambah
    public function create()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        return view('mahasiswa/form', ['title' => 'Tambah Mahasiswa']);
    }

    // Simpan data baru
    public function store(): RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        $rules = [
            'NIM'  => 'required|min_length[3]|max_length[20]|is_unique[mahasiswa.NIM]',
            'NAMA' => 'required|min_length[3]|max_length[100]',
            'UMUR' => 'required|integer|greater_than_equal_to[15]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mahasiswaModel->insert([
            'NIM'  => $this->request->getPost('NIM'),
            'NAMA' => $this->request->getPost('NAMA'),
            'UMUR' => (int) $this->request->getPost('UMUR'),
        ]);

        return redirect()->to('/mahasiswa')->with('message', 'Data berhasil ditambahkan');
    }

    // Tampilkan form edit
    public function edit($id)
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        $row = $this->mahasiswaModel->find($id);
        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mahasiswa tidak ditemukan');
        }

        return view('mahasiswa/form', ['title' => 'Edit Mahasiswa', 'row' => $row]);
    }

    // Update data
    public function update($id): RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        $row = $this->mahasiswaModel->find($id);
        if (!$row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mahasiswa tidak ditemukan');
        }

        $rules = [
            'NIM'  => 'required|min_length[3]|max_length[20]|is_unique[mahasiswa.NIM,ID,' . $id . ']',
            'NAMA' => 'required|min_length[3]|max_length[100]',
            'UMUR' => 'required|integer|greater_than_equal_to[15]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mahasiswaModel->update($id, [
            'NIM'  => $this->request->getPost('NIM'),
            'NAMA' => $this->request->getPost('NAMA'),
            'UMUR' => (int) $this->request->getPost('UMUR'),
        ]);

        return redirect()->to('/mahasiswa')->with('message', 'Data berhasil diperbarui');
    }

    // Hapus data
    public function delete($id): RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        // Pastikan ID ada
        if ($id === null || $id === '') {
            return redirect()->to('/mahasiswa')->with('error', 'ID tidak valid untuk dihapus');
        }

        // Cek data ada (coba find, jika PK numeric akan otomatis cast)
        $row = $this->mahasiswaModel->find($id);
        if (!$row) {
            return redirect()->to('/mahasiswa')->with('error', 'Data tidak ditemukan');
        }

        // Gunakan where eksplisit untuk menghindari delete tanpa where
        $this->mahasiswaModel->where('ID', $row['ID'])->delete();
        return redirect()->to('/mahasiswa')->with('message', 'Data berhasil dihapus');
    }

    private function isLoggedIn(): bool
    {
        return (bool) session()->get('isLoggedIn');
    }
}

