'use client';

import { useEffect, useState, useMemo } from 'react';

interface Course {
  id: number;
  name: string;
  sks: number;
  description: string;
}

const mockAvailableCourses: Course[] = [
  { id: 1, name: 'Kalkulus', sks: 3, description: 'Mata kuliah dasar tentang limit, turunan, dan integral.' },
  { id: 2, name: 'Fisika Dasar', sks: 3, description: 'Mata kuliah pengantar konsep-konsep dasar fisika.' },
  { id: 3, name: 'Algoritma dan Pemrograman', sks: 4, description: 'Mata kuliah tentang logika pemrograman dan struktur data.' },
  { id: 4, name: 'Basis Data', sks: 3, description: 'Mata kuliah tentang desain dan manajemen database relasional.' },
];

const mockEnrolledCourses: Course[] = [
    { id: 1, name: 'Kalkulus', sks: 3, description: 'Mata kuliah dasar tentang limit, turunan, dan integral.' },
];

export default function StudentDashboard() {
  const [availableCourses, setAvailableCourses] = useState<Course[]>([]);
  const [enrolledCourses, setEnrolledCourses] = useState<Course[]>([]);
  const [selectedCourses, setSelectedCourses] = useState<Set<number>>(new Set());
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    setLoading(true);
    setTimeout(() => {
      setAvailableCourses(mockAvailableCourses);
      setEnrolledCourses(mockEnrolledCourses);
      setLoading(false);
    }, 1000);
  }, []);

  const handleCheckboxChange = (courseId: number) => {
    const newSelection = new Set(selectedCourses);
    if (newSelection.has(courseId)) {
      newSelection.delete(courseId);
    } else {
      newSelection.add(courseId);
    }
    setSelectedCourses(newSelection);
  };

  const totalSelectedSKS = useMemo(() => {
    return availableCourses
      .filter(course => selectedCourses.has(course.id))
      .reduce((total, course) => total + course.sks, 0);
  }, [selectedCourses, availableCourses]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    setTimeout(() => {
      const newlyEnrolled = availableCourses.filter(c => selectedCourses.has(c.id));
      setEnrolledCourses(prev => [...prev, ...newlyEnrolled]);
      setSelectedCourses(new Set());
      setSubmitting(false);
      alert(`Berhasil mendaftar untuk ${newlyEnrolled.length} mata kuliah!`);
    }, 1500);
  };

  if (loading) {
    return <div className="text-center p-10">Loading...</div>;
  }

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Dashboard Mahasiswa</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 className="text-2xl font-semibold mb-4">Pendaftaran Mata Kuliah</h2>
          <form onSubmit={handleSubmit} className="p-4 bg-white shadow rounded-lg">
            <div className="space-y-3 mb-4">
              {availableCourses.map(course => {
                const isEnrolled = enrolledCourses.some(ec => ec.id === course.id);
                return (
                  <div key={course.id} className={`p-3 rounded-md ${isEnrolled ? 'bg-gray-200' : 'bg-gray-50'}`}>
                    <label className="flex items-center space-x-3">
                      <input
                        type="checkbox"
                        checked={selectedCourses.has(course.id)}
                        onChange={() => handleCheckboxChange(course.id)}
                        disabled={isEnrolled}
                        className="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 disabled:opacity-50"
                      />
                      <span className="font-medium">{course.name} ({course.sks} SKS)</span>
                      {isEnrolled && <span className="text-xs text-green-600 font-semibold">(Sudah Diambil)</span>}
                    </label>
                  </div>
                );
              })}
            </div>
            <div className="mt-6 p-4 border-t">
              <p className="font-bold text-lg">Total SKS Dipilih: {totalSelectedSKS}</p>
            </div>
            <button type="submit" disabled={selectedCourses.size === 0 || submitting}
                    className="w-full px-4 py-2 mt-4 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400">
              {submitting ? 'Memproses...' : 'Daftar Mata Kuliah Pilihan'}
            </button>
          </form>
        </div>

        <div>
          <h2 className="text-2xl font-semibold mb-4">Mata Kuliah yang Diambil</h2>
          <div className="space-y-2">
            {enrolledCourses.length > 0 ? (
              enrolledCourses.map(course => (
                <div key={course.id} className="p-4 bg-white shadow rounded-lg">
                  <p className="font-bold">{course.name} ({course.sks} SKS)</p>
                  <p className="text-sm text-gray-600">{course.description}</p>
                </div>
              ))
            ) : (
              <p className="text-gray-500">Anda belum mengambil mata kuliah apapun.</p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
