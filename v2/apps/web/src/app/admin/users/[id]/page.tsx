'use client';

import React, { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface User {
  cust_id: string;
  name: string;
  email_id: string;
  user_code: string;
  age: number;
  height: number;
  weight: number;
  gender: string;
  country: string;
  registration_date: string;
  activity_lavel: number;
  allergies: string;
  health_problem: string;
  profile_picture: string;
  status: 'active' | 'inactive';
}

export default function UserDetailPage() {
  const params = useParams();
  const router = useRouter();
  const userId = params.id as string;
  
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [activeTab, setActiveTab] = useState('personal');
  const [isSaving, setIsSaving] = useState(false);
  
  // Form state
  const [formData, setFormData] = useState<Partial<User>>({});

  useEffect(() => {
    const fetchUserData = async () => {
      setLoading(true);
      setError('');
      
      try {
        // Get user data from Convex via adapter
        const response = await convexAdapter.users.getById(userId);
        
        if (response.success) {
          setUser(response.data);
          setFormData(response.data);
        } else {
          setError(response.message || 'Failed to load user data');
        }
      } catch (err) {
        console.error('User fetch error:', err);
        setError('An error occurred while loading user data');
      } finally {
        setLoading(false);
      }
    };

    if (userId) {
      fetchUserData();
    }
  }, [userId]);

  // Handle form input changes
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  // Handle form submission
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    
    try {
      // Update user with Convex via adapter
      const response = await convexAdapter.users.update(userId, formData);
      
      if (response.success) {
        // Update local user state with the saved data
        setUser(formData as User);
        alert('User updated successfully');
      } else {
        setError(response.message || 'Failed to update user');
      }
    } catch (err) {
      console.error('User update error:', err);
      setError('An error occurred while updating user');
    } finally {
      setIsSaving(false);
    }
  };

  // Mock user data for development - will be removed when Convex is fully integrated
  const mockUser: User = {
    cust_id: userId,
    name: "John Doe",
    email_id: "john@example.com",
    user_code: "USR001",
    age: 28,
    height: 175,
    weight: 70,
    gender: "male",
    country: "United States",
    registration_date: "2023-03-15",
    activity_lavel: 3,
    allergies: "Nuts, Dairy",
    health_problem: "None",
    profile_picture: "https://randomuser.me/api/portraits/men/1.jpg",
    status: "active"
  };

  // Use mock data if API data is not available
  const userData = user || mockUser;

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
        <p>{error}</p>
        <button 
          onClick={() => router.push('/admin/users')}
          className="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
        >
          Back to Users
        </button>
      </div>
    );
  }

  return (
    <div>
      {/* Header with user info and actions */}
      <div className="flex justify-between items-center mb-6">
        <div>
          <h1 className="text-3xl font-bold flex items-center">
            <img 
              src={userData.profile_picture || "https://via.placeholder.com/50"} 
              alt={userData.name} 
              className="w-12 h-12 rounded-full mr-4 object-cover"
            />
            {userData.name}
          </h1>
          <p className="text-gray-500">User ID: {userData.cust_id} | Code: {userData.user_code}</p>
        </div>
        <div className="flex gap-2">
          <Link href={`/admin/users/viewMeal/${userData.cust_id}/week`} className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Manage Meals
          </Link>
          <Link href={`/admin/users/viewWorkout/${userData.cust_id}/week`} className="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
            Manage Workouts
          </Link>
          <button 
            onClick={() => router.push('/admin/users')}
            className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
          >
            Back to Users
          </button>
        </div>
      </div>

      {/* Tabs */}
      <div className="border-b mb-6">
        <nav className="flex -mb-px">
          <button
            onClick={() => setActiveTab('personal')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'personal'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Personal Info
          </button>
          <button
            onClick={() => setActiveTab('fitness')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'fitness'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Fitness & Health
          </button>
          <button
            onClick={() => setActiveTab('subscription')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'subscription'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Subscription
          </button>
        </nav>
      </div>

      {/* User Form */}
      <div className="bg-white shadow rounded-lg p-6">
        <form onSubmit={handleSubmit}>
          {/* Personal Info Tab */}
          {activeTab === 'personal' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">
                  Full Name
                </label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value={formData.name || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="email_id" className="block text-sm font-medium text-gray-700 mb-1">
                  Email Address
                </label>
                <input
                  type="email"
                  id="email_id"
                  name="email_id"
                  value={formData.email_id || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="age" className="block text-sm font-medium text-gray-700 mb-1">
                  Age
                </label>
                <input
                  type="number"
                  id="age"
                  name="age"
                  value={formData.age || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="gender" className="block text-sm font-medium text-gray-700 mb-1">
                  Gender
                </label>
                <select
                  id="gender"
                  name="gender"
                  value={formData.gender || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              
              <div>
                <label htmlFor="country" className="block text-sm font-medium text-gray-700 mb-1">
                  Country
                </label>
                <input
                  type="text"
                  id="country"
                  name="country"
                  value={formData.country || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-1">
                  Status
                </label>
                <select
                  id="status"
                  name="status"
                  value={formData.status || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              
              <div className="col-span-2">
                <p className="text-sm text-gray-500 mb-4">
                  Registration Date: <span className="font-medium">{userData.registration_date}</span>
                </p>
              </div>
            </div>
          )}

          {/* Fitness Tab */}
          {activeTab === 'fitness' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label htmlFor="height" className="block text-sm font-medium text-gray-700 mb-1">
                  Height (cm)
                </label>
                <input
                  type="number"
                  step="0.1"
                  id="height"
                  name="height"
                  value={formData.height || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="weight" className="block text-sm font-medium text-gray-700 mb-1">
                  Weight (kg)
                </label>
                <input
                  type="number"
                  step="0.1"
                  id="weight"
                  name="weight"
                  value={formData.weight || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <div>
                <label htmlFor="activity_lavel" className="block text-sm font-medium text-gray-700 mb-1">
                  Activity Level (1-5)
                </label>
                <select
                  id="activity_lavel"
                  name="activity_lavel"
                  value={formData.activity_lavel || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="1">1 - Sedentary</option>
                  <option value="2">2 - Lightly Active</option>
                  <option value="3">3 - Moderately Active</option>
                  <option value="4">4 - Very Active</option>
                  <option value="5">5 - Extremely Active</option>
                </select>
              </div>
              
              <div className="col-span-2">
                <label htmlFor="allergies" className="block text-sm font-medium text-gray-700 mb-1">
                  Allergies
                </label>
                <textarea
                  id="allergies"
                  name="allergies"
                  value={formData.allergies || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  rows={3}
                ></textarea>
              </div>
              
              <div className="col-span-2">
                <label htmlFor="health_problem" className="block text-sm font-medium text-gray-700 mb-1">
                  Health Problems
                </label>
                <textarea
                  id="health_problem"
                  name="health_problem"
                  value={formData.health_problem || ''}
                  onChange={handleInputChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                  rows={3}
                ></textarea>
              </div>
            </div>
          )}

          {/* Subscription Tab */}
          {activeTab === 'subscription' && (
            <div className="space-y-6">
              <p className="text-gray-500">No subscription information available for this user.</p>
              {/* This would be expanded with actual subscription data in a real implementation */}
            </div>
          )}

          {/* Form Actions */}
          <div className="mt-8 flex justify-end">
            <button
              type="button"
              onClick={() => router.push('/admin/users')}
              className="mr-4 px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={isSaving}
              className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
            >
              {isSaving ? 'Saving...' : 'Save Changes'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
} 