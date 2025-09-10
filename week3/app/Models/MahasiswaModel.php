<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table      = 'mahasiswa';
    protected $primaryKey = 'ID';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['NIM', 'NAMA', 'UMUR'];

    // Timestamps (optional)
    protected $useTimestamps = false;

    // Validation rules default (can also validate in controller)
    protected $validationRules = [
        'NIM'  => 'required|min_length[3]|max_length[20]',
        'NAMA' => 'required|min_length[3]|max_length[100]',
        'UMUR' => 'required|integer|greater_than_equal_to[15]|less_than_equal_to[100]',
    ];

    public function getMahasiswa()
    {
        return $this->orderBy('NIM', 'ASC')->findAll();
    }
}
