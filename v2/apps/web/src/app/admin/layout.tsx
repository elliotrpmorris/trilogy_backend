'use client';

import React from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useClerk, useAuth } from "@clerk/nextjs";

export default function AdminLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const router = useRouter();
  const { signOut } = useClerk();
  const { isLoaded, userId } = useAuth();
  
  const handleSignOut = () => {
    signOut(() => router.push('/'));
  };


  return (
    <div className="flex min-h-screen">
      {/* Sidebar */}
      <aside className="w-64 bg-gray-800 text-white">
        <div className="p-4 font-bold text-xl">
          <Link href="/admin/dashboard">Trilogy Admin</Link>
        </div>
        <nav className="mt-6">
          <ul className="space-y-2 px-4">
            <li>
              <Link href="/admin/dashboard" className="block py-2 hover:text-gray-300">
                Dashboard
              </Link>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <h3 className="font-medium text-gray-400 text-sm">User Management</h3>
              <Link href="/admin/users" className="block py-2 hover:text-gray-300">
                Users
              </Link>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <h3 className="font-medium text-gray-400 text-sm">Payment Management</h3>
              <ul className="space-y-1">
                <li>
                  <Link href="/admin/orders" className="block py-1 hover:text-gray-300">
                    Orders
                  </Link>
                </li>
                <li>
                  <Link href="/admin/payments" className="block py-1 hover:text-gray-300">
                    Payments
                  </Link>
                </li>
              </ul>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <h3 className="font-medium text-gray-400 text-sm">Meal Management</h3>
              <ul className="space-y-1">
                <li>
                  <Link href="/admin/mealtype" className="block py-1 hover:text-gray-300">
                    Meal Types
                  </Link>
                </li>
                <li>
                  <Link href="/admin/foodtype" className="block py-1 hover:text-gray-300">
                    Food Types
                  </Link>
                </li>
                <li>
                  <Link href="/admin/diettype" className="block py-1 hover:text-gray-300">
                    Diet Types
                  </Link>
                </li>
                <li>
                  <Link href="/admin/nutrition" className="block py-1 hover:text-gray-300">
                    Nutrition
                  </Link>
                </li>
                <li>
                  <Link href="/admin/meals" className="block py-1 hover:text-gray-300">
                    Meals List
                  </Link>
                </li>
              </ul>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <h3 className="font-medium text-gray-400 text-sm">Workout Management</h3>
              <ul className="space-y-1">
                <li>
                  <Link href="/admin/workout/workoutlevel" className="block py-1 hover:text-gray-300">
                    Workout Level
                  </Link>
                </li>
                <li>
                  <Link href="/admin/workout/workoutcoach" className="block py-1 hover:text-gray-300">
                    Workout Coach
                  </Link>
                </li>
                <li>
                  <Link href="/admin/workout/routine" className="block py-1 hover:text-gray-300">
                    Workout Routine
                  </Link>
                </li>
                <li>
                  <Link href="/admin/workout" className="block py-1 hover:text-gray-300">
                    Exercises
                  </Link>
                </li>
              </ul>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <h3 className="font-medium text-gray-400 text-sm">Marketing</h3>
              <ul className="space-y-1">
                <li>
                  <Link href="/admin/coupon" className="block py-1 hover:text-gray-300">
                    Coupon Codes
                  </Link>
                </li>
                <li>
                  <Link href="/admin/competition" className="block py-1 hover:text-gray-300">
                    Competition
                  </Link>
                </li>
              </ul>
            </li>
            <li className="pt-2 border-t border-gray-700">
              <button 
                onClick={handleSignOut}
                className="w-full text-left block py-2 hover:text-gray-300"
              >
                Logout
              </button>
            </li>
          </ul>
        </nav>
      </aside>

      {/* Main content */}
      <main className="flex-1 bg-gray-100">
        <div className="p-6">
          {children}
        </div>
      </main>
    </div>
  );
} 