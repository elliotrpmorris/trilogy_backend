'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import convexAdapter from '@/lib/convexAdapter';

// Types for our dashboard data
interface DashboardStats {
  totalUsers: number;
  activeUsers: number;
  totalOrders: number;
  totalRevenue: number;
  recentUsers: {
    cust_id: string;
    name: string;
    email_id: string;
    registration_date: string;
  }[];
  recentOrders: {
    order_id: string;
    cust_id: string;
    name: string;
    amount: number;
    date: string;
    status: string;
  }[];
}

export default function AdminDashboard() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchDashboardData = async () => {
      setLoading(true);
      setError('');
      
      try {
        // Get dashboard data from Convex
        const response = await convexAdapter.admin.getDashboard();
        
        if (response.success) {
          setStats(response.data);
        } else {
          setError(response.message || 'Failed to load dashboard data');
        }
      } catch (err) {
        console.error('Dashboard error:', err);
        setError('An error occurred while loading dashboard data');
      } finally {
        setLoading(false);
      }
    };

    fetchDashboardData();
  }, []);

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
        {error}
      </div>
    );
  }

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderStats: DashboardStats = {
    totalUsers: 2456,
    activeUsers: 1872,
    totalOrders: 1256,
    totalRevenue: 85640,
    recentUsers: [
      { cust_id: "1", name: 'John Doe', email_id: 'john@example.com', registration_date: '2023-03-15' },
      { cust_id: "2", name: 'Jane Smith', email_id: 'jane@example.com', registration_date: '2023-03-14' },
      { cust_id: "3", name: 'Bob Johnson', email_id: 'bob@example.com', registration_date: '2023-03-13' },
      { cust_id: "4", name: 'Alice Brown', email_id: 'alice@example.com', registration_date: '2023-03-12' },
    ],
    recentOrders: [
      { order_id: "101", cust_id: "1", name: 'John Doe', amount: 99, date: '2023-03-15', status: 'Completed' },
      { order_id: "102", cust_id: "2", name: 'Jane Smith', amount: 149, date: '2023-03-14', status: 'Completed' },
      { order_id: "103", cust_id: "3", name: 'Bob Johnson', amount: 199, date: '2023-03-13', status: 'Processing' },
      { order_id: "104", cust_id: "4", name: 'Alice Brown', amount: 99, date: '2023-03-12', status: 'Completed' },
    ]
  };

  // Use placeholder data if API data is not available
  const dashboardData = stats || placeholderStats;

  return (
    <div className="max-w-7xl mx-auto">
      <h1 className="text-3xl font-bold mb-8 text-gray-800">Dashboard</h1>
      
      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div className="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
          <h2 className="text-lg font-medium text-gray-500">Total Users</h2>
          <p className="text-3xl font-bold text-gray-900 mt-2">{dashboardData.totalUsers}</p>
          <div className="w-full h-1 bg-blue-500 mt-4 rounded-full"></div>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
          <h2 className="text-lg font-medium text-gray-500">Active Users</h2>
          <p className="text-3xl font-bold text-gray-900 mt-2">{dashboardData.activeUsers}</p>
          <div className="w-full h-1 bg-green-500 mt-4 rounded-full"></div>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
          <h2 className="text-lg font-medium text-gray-500">Total Orders</h2>
          <p className="text-3xl font-bold text-gray-900 mt-2">{dashboardData.totalOrders}</p>
          <div className="w-full h-1 bg-purple-500 mt-4 rounded-full"></div>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-100">
          <h2 className="text-lg font-medium text-gray-500">Total Revenue</h2>
          <p className="text-3xl font-bold text-gray-900 mt-2">£{dashboardData.totalRevenue.toLocaleString()}</p>
          <div className="w-full h-1 bg-amber-500 mt-4 rounded-full"></div>
        </div>
      </div>
      
      {/* Recent Users */}
      <div className="bg-white rounded-xl shadow-md mb-8 border border-gray-100">
        <div className="flex justify-between items-center p-6 border-b">
          <h2 className="text-xl font-semibold text-gray-800">Recent Users</h2>
          <Link href="/admin/users" className="text-blue-600 hover:text-blue-800 font-medium flex items-center">
            View All
            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
            </svg>
          </Link>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {dashboardData.recentUsers.map((user) => (
                <tr key={user.cust_id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.cust_id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-gray-800">{user.name}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-gray-800">{user.email_id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{user.registration_date}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm">
                    <Link href={`/admin/users/${user.cust_id}`} className="text-blue-600 hover:text-blue-800 font-medium">
                      View
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
      
      {/* Recent Orders */}
      <div className="bg-white rounded-xl shadow-md border border-gray-100">
        <div className="flex justify-between items-center p-6 border-b">
          <h2 className="text-xl font-semibold text-gray-800">Recent Orders</h2>
          <Link href="/admin/orders" className="text-blue-600 hover:text-blue-800 font-medium flex items-center">
            View All
            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
            </svg>
          </Link>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {dashboardData.recentOrders.map((order) => (
                <tr key={order.order_id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{order.order_id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-gray-800">{order.name}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-gray-800">${order.amount}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{order.date}</td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      order.status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                    }`}>
                      {order.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm">
                    <Link href={`/admin/orders/${order.order_id}`} className="text-blue-600 hover:text-blue-800 font-medium">
                      View
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
} 