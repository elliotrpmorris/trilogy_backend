'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface DietType {
  id: string;
  name: string;
  description: string;
  order: number;
  createdAt: number;
  updatedAt: number;
}

export default function DietTypesPage() {
  const router = useRouter();
  
  const [dietTypes, setDietTypes] = useState<DietType[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  
  // Form state
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<string | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    description: '',
    order: 0
  });

  // Fetch diet types on component mount
  useEffect(() => {
    fetchDietTypes();
  }, []);

  const fetchDietTypes = async () => {
    setLoading(true);
    setError('');
    
    try {
      const response = await convexAdapter.meals.getDietTypes();
      
      if (response.success) {
        setDietTypes(response.data);
      } else {
        setError(response.message || 'Failed to load diet types');
      }
    } catch (err) {
      console.error('Diet types fetch error:', err);
      setError('An error occurred while loading diet types');
    } finally {
      setLoading(false);
    }
  };

  const openCreateForm = () => {
    setFormData({
      name: '',
      description: '',
      order: dietTypes.length + 1
    });
    setEditingId(null);
    setIsFormOpen(true);
  };

  const openEditForm = (dietType: DietType) => {
    setFormData({
      name: dietType.name,
      description: dietType.description || '',
      order: dietType.order || 0
    });
    setEditingId(dietType.id);
    setIsFormOpen(true);
  };

  const closeForm = () => {
    setIsFormOpen(false);
    setEditingId(null);
    setFormData({
      name: '',
      description: '',
      order: 0
    });
  };

  const handleFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === 'order' ? parseInt(value) || 0 : value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!formData.name.trim()) {
      setError('Name is required');
      return;
    }
    
    // For now, we'll display a message that this functionality is not yet implemented
    setError('Diet type management functionality is currently being implemented in the backend');
    
    // When the backend is ready, we'll use code like this:
    /*
    try {
      let response;
      
      if (editingId) {
        // Update existing diet type
        response = await convexAdapter.meals.updateType(editingId, formData);
      } else {
        // Create new diet type
        response = await convexAdapter.meals.createType(formData);
      }
      
      if (response.success) {
        fetchDietTypes();
        closeForm();
      } else {
        setError(response.message || 'Operation failed');
      }
    } catch (err) {
      console.error('Form submission error:', err);
      setError('An error occurred while processing your request');
    }
    */
  };

  const handleDelete = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this diet type? This may affect associated meals.')) {
      // For now, we'll display a message that this functionality is not yet implemented
      setError('Diet type deletion functionality is currently being implemented in the backend');
      
      // When the backend is ready, we'll use code like this:
      /*
      try {
        const response = await convexAdapter.meals.deleteType(id);
        
        if (response.success) {
          fetchDietTypes();
        } else {
          setError(response.message || 'Failed to delete diet type');
        }
      } catch (err) {
        console.error('Error deleting diet type:', err);
        setError('An error occurred while deleting the diet type');
      }
      */
    }
  };

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderDietTypes: DietType[] = [
    { id: "1", name: 'Vegetarian', description: 'No meat or fish', order: 1, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "2", name: 'Vegan', description: 'No animal products', order: 2, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "3", name: 'Keto', description: 'Low-carb, high-fat', order: 3, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "4", name: 'Paleo', description: 'Based on prehistoric human diet', order: 4, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "5", name: 'High protein', description: 'Protein-focused diet', order: 5, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "6", name: 'Pescetarian', description: 'Fish but no other meat', order: 6, createdAt: Date.now(), updatedAt: Date.now() },
  ];

  // Use placeholder data if API data is not available
  const dietTypesData = dietTypes.length > 0 ? dietTypes : placeholderDietTypes;

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Diet Types</h1>
            <p className="mt-2 text-sm text-gray-500">Manage your diet categories and their display order</p>
          </div>
          <button
            onClick={openCreateForm}
            className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clipRule="evenodd" />
            </svg>
            Add Diet Type
          </button>
        </div>

        {error && (
          <div className="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
            <div className="flex">
              <div className="flex-shrink-0">
                <svg className="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                </svg>
              </div>
              <div className="ml-3">
                <p className="text-sm text-red-700">{error}</p>
              </div>
            </div>
          </div>
        )}

        {/* Form Modal */}
        {isFormOpen && (
          <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
              <div className="px-6 py-4 border-b border-gray-200">
                <h2 className="text-xl font-semibold text-gray-900">
                  {editingId ? 'Edit Diet Type' : 'Add Diet Type'}
                </h2>
              </div>
              
              <form onSubmit={handleSubmit} className="p-6">
                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="name">
                      Name <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      value={formData.name}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      required
                    />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="description">
                      Description
                    </label>
                    <textarea
                      id="description"
                      name="description"
                      value={formData.description}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      rows={3}
                    />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="order">
                      Display Order
                    </label>
                    <input
                      type="number"
                      id="order"
                      name="order"
                      value={formData.order}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      min="0"
                    />
                  </div>
                </div>
                
                <div className="mt-6 flex justify-end space-x-3">
                  <button
                    type="button"
                    onClick={closeForm}
                    className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {editingId ? 'Update' : 'Save'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}

        {/* Diet Types Table */}
        <div className="bg-white shadow rounded-lg overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-200">
            <h2 className="text-lg font-medium text-gray-900">All Diet Types</h2>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {dietTypesData.map((dietType) => (
                  <tr key={dietType.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">{dietType.name}</div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="text-sm text-gray-500">{dietType.description || '-'}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500">{dietType.order || '-'}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                      <button
                        onClick={() => openEditForm(dietType)}
                        className="text-indigo-600 hover:text-indigo-900"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => handleDelete(dietType.id)}
                        className="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
} 