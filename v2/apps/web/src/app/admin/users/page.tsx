'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { useRouter, usePathname, useSearchParams } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface User {
  cust_id: string;
  name: string;
  email_id: string;
  user_code: string;
  age: number;
  gender: string;
  registration_date: string;
  activity_lavel: number;
  status: 'active' | 'inactive';
}

export default function UsersPage() {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  
  // Pagination state
  const [totalUsers, setTotalUsers] = useState(0);
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(10);
  
  // Search and filter state
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');

  // Update query parameters when filters change
  const updateQueryParams = (params: Record<string, string>) => {
    const newParams = new URLSearchParams(searchParams);
    
    // Update or add new parameters
    Object.entries(params).forEach(([key, value]) => {
      if (value) {
        newParams.set(key, value);
      } else {
        newParams.delete(key);
      }
    });
    
    router.push(`${pathname}?${newParams.toString()}`);
  };

  // Handle search form submission
  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    updateQueryParams({ search: searchTerm, page: '1' });
  };

  // Handle filter change
  const handleFilterChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setFilterStatus(e.target.value);
    updateQueryParams({ status: e.target.value, page: '1' });
  };

  // Handle page change
  const handlePageChange = (newPage: number) => {
    setPage(newPage);
    updateQueryParams({ page: newPage.toString() });
  };

  useEffect(() => {
    const fetchUsers = async () => {
      setLoading(true);
      setError('');
      
      try {
        // Get query parameters
        const search = searchParams.get('search') || '';
        const status = searchParams.get('status') || 'all';
        const currentPage = parseInt(searchParams.get('page') || '1', 10);
        
        // Update local state with URL parameters
        setSearchTerm(search);
        setFilterStatus(status);
        setPage(currentPage);
        
        // Fetch users from Convex via adapter
        const response = await convexAdapter.users.getAll({
          page: currentPage,
          per_page: perPage,
          search: search,
          status: status
        });
        
        if (response.success) {
          setUsers(response.data.users);
          setTotalUsers(response.data.total);
        } else {
          setError(response.message || 'Failed to load users');
        }
      } catch (err) {
        console.error('Users fetch error:', err);
        setError('An error occurred while loading users');
      } finally {
        setLoading(false);
      }
    };

    fetchUsers();
  }, [searchParams, perPage]);

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderUsers: User[] = [
    { cust_id: "1", name: 'John Doe', email_id: 'john@example.com', user_code: 'USR001', age: 28, gender: 'male', registration_date: '2023-03-15', activity_lavel: 3, status: 'active' },
    { cust_id: "2", name: 'Jane Smith', email_id: 'jane@example.com', user_code: 'USR002', age: 32, gender: 'female', registration_date: '2023-03-14', activity_lavel: 2, status: 'active' },
    { cust_id: "3", name: 'Bob Johnson', email_id: 'bob@example.com', user_code: 'USR003', age: 41, gender: 'male', registration_date: '2023-03-13', activity_lavel: 1, status: 'inactive' },
    { cust_id: "4", name: 'Alice Brown', email_id: 'alice@example.com', user_code: 'USR004', age: 35, gender: 'female', registration_date: '2023-03-12', activity_lavel: 4, status: 'active' },
    { cust_id: "5", name: 'Charlie Wilson', email_id: 'charlie@example.com', user_code: 'USR005', age: 29, gender: 'male', registration_date: '2023-03-11', activity_lavel: 3, status: 'active' },
    { cust_id: "6", name: 'Diana Miller', email_id: 'diana@example.com', user_code: 'USR006', age: 27, gender: 'female', registration_date: '2023-03-10', activity_lavel: 2, status: 'inactive' },
    { cust_id: "7", name: 'Edward Davis', email_id: 'edward@example.com', user_code: 'USR007', age: 33, gender: 'male', registration_date: '2023-03-09', activity_lavel: 1, status: 'active' },
    { cust_id: "8", name: 'Fiona Clark', email_id: 'fiona@example.com', user_code: 'USR008', age: 30, gender: 'female', registration_date: '2023-03-08', activity_lavel: 4, status: 'active' },
    { cust_id: "9", name: 'George White', email_id: 'george@example.com', user_code: 'USR009', age: 38, gender: 'male', registration_date: '2023-03-07', activity_lavel: 3, status: 'inactive' },
    { cust_id: "10", name: 'Hannah Green', email_id: 'hannah@example.com', user_code: 'USR010', age: 25, gender: 'female', registration_date: '2023-03-06', activity_lavel: 2, status: 'active' },
  ];

  // Use placeholder data if API data is not available
  const userData = users.length > 0 ? users : placeholderUsers;
  const totalPages = Math.ceil(totalUsers > 0 ? totalUsers : 100 / perPage);

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Users</h1>
        <button onClick={() => router.push('/admin/users/export')} className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Export Users
        </button>
      </div>

      {/* Search and Filter */}
      <div className="bg-white p-4 rounded-lg shadow mb-6">
        <div className="flex flex-wrap items-center gap-4">
          <form onSubmit={handleSearch} className="flex flex-1 gap-2">
            <input
              type="text"
              placeholder="Search by name or email"
              className="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Search
            </button>
          </form>
          
          <div className="flex items-center">
            <label htmlFor="statusFilter" className="mr-2">Status:</label>
            <select
              id="statusFilter"
              className="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={filterStatus}
              onChange={handleFilterChange}
            >
              <option value="all">All Users</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      {/* Error Message */}
      {error && (
        <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
          {error}
        </div>
      )}

      {/* Users Table */}
      <div className="bg-white rounded-lg shadow mb-6">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {loading ? (
                <tr>
                  <td colSpan={8} className="px-6 py-4 text-center">
                    <div className="flex justify-center items-center h-20">
                      <div className="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                    </div>
                  </td>
                </tr>
              ) : userData.length === 0 ? (
                <tr>
                  <td colSpan={8} className="px-6 py-4 text-center text-gray-500">
                    No users found matching your criteria
                  </td>
                </tr>
              ) : (
                userData.map((user) => (
                  <tr key={user.cust_id}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.cust_id}</td>
                    <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                    <td className="px-6 py-4 whitespace-nowrap">{user.email_id}</td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.age}</td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{user.gender}</td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.registration_date}</td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                      }`}>
                        {user.status === 'active' ? 'Active' : 'Inactive'}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm">
                      <div className="flex space-x-2">
                        <Link href={`/admin/users/${user.cust_id}`} className="text-blue-600 hover:text-blue-800">
                          View
                        </Link>
                        <Link href={`/admin/users/viewMeal/${user.cust_id}/week`} className="text-green-600 hover:text-green-800">
                          Meals
                        </Link>
                        <Link href={`/admin/users/viewWorkout/${user.cust_id}/week`} className="text-purple-600 hover:text-purple-800">
                          Workouts
                        </Link>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Pagination */}
      <div className="flex items-center justify-between bg-white p-4 rounded-lg shadow">
        <div className="flex-1 flex justify-between sm:hidden">
          <button
            onClick={() => handlePageChange(page - 1)}
            disabled={page <= 1}
            className={`relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
              page <= 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'
            }`}
          >
            Previous
          </button>
          <button
            onClick={() => handlePageChange(page + 1)}
            disabled={page >= totalPages}
            className={`ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
              page >= totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'
            }`}
          >
            Next
          </button>
        </div>
        <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p className="text-sm text-gray-700">
              Showing <span className="font-medium">{(page - 1) * perPage + 1}</span> to{' '}
              <span className="font-medium">{Math.min(page * perPage, totalUsers || 100)}</span> of{' '}
              <span className="font-medium">{totalUsers || 100}</span> results
            </p>
          </div>
          <div>
            <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                onClick={() => handlePageChange(page - 1)}
                disabled={page <= 1}
                className={`relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 text-sm font-medium ${
                  page <= 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-500 hover:bg-gray-50'
                }`}
              >
                <span className="sr-only">Previous</span>
                <span>←</span>
              </button>
              
              {/* Page numbers */}
              {[...Array(totalPages > 0 ? totalPages : 10)].map((_, i) => {
                // Show 5 page buttons max
                if (totalPages > 7) {
                  // Always show first, last, and current page
                  // For others, show 2 before and 2 after current page
                  const pageNum = i + 1;
                  if (
                    pageNum === 1 ||
                    pageNum === totalPages ||
                    (pageNum >= page - 2 && pageNum <= page + 2)
                  ) {
                    return (
                      <button
                        key={i}
                        onClick={() => handlePageChange(pageNum)}
                        className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                          page === pageNum
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        }`}
                      >
                        {pageNum}
                      </button>
                    );
                  } else if (
                    (pageNum === page - 3 && pageNum > 1) ||
                    (pageNum === page + 3 && pageNum < totalPages)
                  ) {
                    return (
                      <span
                        key={i}
                        className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700"
                      >
                        ...
                      </span>
                    );
                  }
                  return null;
                }
                
                // Show all pages if total pages <= 7
                return (
                  <button
                    key={i}
                    onClick={() => handlePageChange(i + 1)}
                    className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                      page === i + 1
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    }`}
                  >
                    {i + 1}
                  </button>
                );
              })}
              
              <button
                onClick={() => handlePageChange(page + 1)}
                disabled={page >= totalPages}
                className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 text-sm font-medium ${
                  page >= totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-500 hover:bg-gray-50'
                }`}
              >
                <span className="sr-only">Next</span>
                <span>→</span>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  );
} 