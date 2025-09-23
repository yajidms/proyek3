'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useEffect, useState } from 'react';
import { jwtDecode } from 'jwt-decode';

interface DecodedToken {
  role: string;
  exp: number;
}

export default function AppLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const [userRole, setUserRole] = useState<string | null>(null);

  useEffect(() => {
    const token = document.cookie.split('; ').find(row => row.startsWith('token='))?.split('=')[1];
    if (token) {
      try {
        const decoded = jwtDecode<DecodedToken>(token);
        setUserRole(decoded.role);
      } catch (error) {
        console.error("Invalid token");
        setUserRole(null);
      }
    } else {
        setUserRole(null);
    }
  }, [pathname]);

  const navLinks = userRole === 'admin' 
    ? [{ href: '/admin/dashboard', label: 'Dashboard' }, { href: '/admin/courses', label: 'Mata Kuliah' }]
    : [{ href: '/student/dashboard', label: 'Dashboard' }];

  const isActive = (href: string) => pathname === href;

  if (pathname === '/login' || !userRole) {
    return <>{children}</>;
  }

  return (
    <div className="min-h-screen bg-gray-50 text-gray-800">
      <nav className="bg-white shadow-md">
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-center py-4">
            <div className="text-xl font-bold text-blue-600">Akademik</div>
            <div className="hidden md:flex items-center space-x-4">
              {navLinks.map(link => (
                <Link key={link.href} href={link.href}
                   className={`px-3 py-2 rounded-md text-sm font-medium ${
                     isActive(link.href)
                       ? 'bg-blue-100 text-blue-700'
                       : 'text-gray-700 hover:bg-gray-100'
                   }`}>
                    {link.label}
                </Link>
              ))}
               <button onClick={() => {
                   document.cookie = 'token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
                   window.location.href = '/login';
               }} className="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50">
                   Logout
               </button>
            </div>
          </div>
        </div>
      </nav>
      <main className="container mx-auto p-4">
        {children}
      </main>
    </div>
  );
}
