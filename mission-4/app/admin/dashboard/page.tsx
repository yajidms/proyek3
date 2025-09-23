'use client';

import { useEffect, useState } from 'react';

interface Course {
  id: number;
  name: string;
  sks: number;
  description: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

interface CourseFormState {
    id: number | null;
    name: string;
    sks: string;
    description: string;
}

const mockCourses: Course[] = [
  { id: 1, name: 'Kalkulus', sks: 3, description: 'Mata kuliah dasar tentang limit, turunan, dan integral.' },
  { id: 2, name: 'Fisika Dasar', sks: 3, description: 'Mata kuliah pengantar konsep-konsep dasar fisika.' },
  { id: 3, name: 'Algoritma dan Pemrograman', sks: 4, description: 'Mata kuliah tentang logika pemrograman dan struktur data.' },
  { id: 4, name: 'Basis Data', sks: 3, description: 'Mata kuliah tentang desain dan manajemen database relasional.' },
];

const mockUsers: User[] = [
    { id: 2, name: 'Mahasiswa 1', email: 'hutao@email.com', role: 'mahasiswa' },
];


export default function AdminDashboard() {
  const [courses, setCourses] = useState<Course[]>(mockCourses);
  const [users, setUsers] = useState<User[]>(mockUsers);
  const [loading, setLoading] = useState(true);
  
  const [form, setForm] = useState<CourseFormState>({ id: null, name: '', sks: '', description: '' });
  const [errors, setErrors] = useState({ name: '', sks: '' });

  const [showConfirm, setShowConfirm] = useState(false);
  const [courseToDelete, setCourseToDelete] = useState<Course | null>(null);

  useEffect(() => {
    setTimeout(() => {
      setLoading(false);
    }, 1500);
  }, []);

  const validateForm = () => {
    let valid = true;
    const newErrors = { name: '', sks: '' };
    if (!form.name.trim()) {
      newErrors.name = 'Nama mata kuliah tidak boleh kosong.';
      valid = false;
    }
    if (!form.sks || isNaN(Number(form.sks)) || Number(form.sks) <= 0) {
      newErrors.sks = 'SKS harus berupa angka positif.';
      valid = false;
    }
    setErrors(newErrors);
    return valid;
  };

  const handleFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setForm({ ...form, [name]: value });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!validateForm()) return;

    setLoading(true);
    setTimeout(() => {
        if (form.id) {
            setCourses(courses.map(c => c.id === form.id ? { ...c, name: form.name, sks: Number(form.sks), description: form.description } : c));
        } else {
            const newCourse: Course = { 
                id: Date.now(), 
                name: form.name,
                sks: Number(form.sks),
                description: form.description
            };
            setCourses([...courses, newCourse]);
        }
        setForm({ id: null, name: '', sks: '', description: '' });
        setLoading(false);
    }, 500);
  };

  const handleEdit = (course: Course) => {
    setForm({ ...course, sks: String(course.sks) });
  };

  const handleDeleteClick = (course: Course) => {
    setCourseToDelete(course);
    setShowConfirm(true);
  };

  const confirmDelete = () => {
    if (!courseToDelete) return;
    setLoading(true);
    setTimeout(() => {
        setCourses(courses.filter(c => c.id !== courseToDelete.id));
        setShowConfirm(false);
        setCourseToDelete(null);
        setLoading(false);
    }, 500);
  };

  if (loading && !showConfirm) {
    return <div className="text-center p-10">Loading...</div>;
  }

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 className="text-2xl font-semibold mb-4 text-gray-700">Daftar Mata Kuliah</h2>
          <div className="space-y-2">
            {courses.map(course => (
              <div key={course.id} className="p-4 bg-white shadow rounded-lg flex justify-between items-center">
                <div>
                  <p className="font-bold text-gray-900">{course.name} ({course.sks} SKS)</p>
                  <p className="text-sm text-gray-600">{course.description}</p>
                </div>
                <div className="space-x-2">
                  <button onClick={() => handleEdit(course)} className="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                  <button onClick={() => handleDeleteClick(course)} className="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                </div>
              </div>
            ))}
          </div>
        </div>

        <div>
          <h2 className="text-2xl font-semibold mb-4 text-gray-700">{form.id ? 'Edit' : 'Tambah'} Mata Kuliah</h2>
          <form onSubmit={handleSubmit} className="p-4 bg-white shadow rounded-lg space-y-4">
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700">Nama</label>
              <input type="text" name="name" value={form.name} onChange={handleFormChange}
                     className={`mt-1 block w-full border rounded-md p-2 bg-white text-gray-900 ${errors.name ? 'border-red-500' : 'border-gray-300'}`} />
              {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
            </div>
            <div>
              <label htmlFor="sks" className="block text-sm font-medium text-gray-700">SKS</label>
              <input type="number" name="sks" value={form.sks} onChange={handleFormChange}
                     className={`mt-1 block w-full border rounded-md p-2 bg-white text-gray-900 ${errors.sks ? 'border-red-500' : 'border-gray-300'}`} />
              {errors.sks && <p className="text-red-500 text-xs mt-1">{errors.sks}</p>}
            </div>
            <div>
              <label htmlFor="description" className="block text-sm font-medium text-gray-700">Deskripsi</label>
              <textarea name="description" value={form.description} onChange={handleFormChange}
                        className="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-white text-gray-900"></textarea>
            </div>
            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full font-semibold">
              {loading ? 'Menyimpan...' : (form.id ? 'Update' : 'Simpan')}
            </button>
          </form>
        </div>
      </div>

      <div className="mt-10">
        <h2 className="text-2xl font-semibold mb-4 text-gray-700">Daftar Mahasiswa</h2>
        <div className="bg-white shadow rounded-lg overflow-hidden">
          <table className="min-w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="p-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                <th className="p-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                <th className="p-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
              </tr>
            </thead>
            <tbody>
              {users.map(user => (
                <tr key={user.id} className="border-b border-gray-200">
                  <td className="p-4 text-gray-800">{user.id}</td>
                  <td className="p-4 text-gray-800">{user.name}</td>
                  <td className="p-4 text-gray-800">{user.email}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {showConfirm && courseToDelete && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg shadow-xl">
            <h3 className="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
            <p className="my-4 text-gray-700">
              Anda yakin ingin menghapus mata kuliah <strong>{courseToDelete.name} ({courseToDelete.sks} SKS)</strong>?
            </p>
            <div className="flex justify-end space-x-4">
              <button onClick={() => setShowConfirm(false)} className="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</button>
              <button onClick={confirmDelete} className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                {loading ? 'Menghapus...' : 'Hapus'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
