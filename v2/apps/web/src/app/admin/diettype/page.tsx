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
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Diet Types</h1>
        <button
          onClick={openCreateForm}
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Add Diet Type
        </button>
      </div>

      {error && (
        <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
          {error}
        </div>
      )}

      {/* Form Modal */}
      {isFormOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 className="text-xl font-semibold mb-4">
              {editingId ? 'Edit Diet Type' : 'Add Diet Type'}
            </h2>
            
            <form onSubmit={handleSubmit}>
              <div className="mb-4">
                <label className="block text-gray-700 mb-2" htmlFor="name">
                  Name <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value={formData.name}
                  onChange={handleFormChange}
                  className="w-full p-2 border border-gray-300 rounded"
                  required
                />
              </div>
              
              <div className="mb-4">
                <label className="block text-gray-700 mb-2" htmlFor="description">
                  Description
                </label>
                <textarea
                  id="description"
                  name="description"
                  value={formData.description}
                  onChange={handleFormChange}
                  className="w-full p-2 border border-gray-300 rounded"
                  rows={3}
                />
              </div>
              
              <div className="mb-4">
                <label className="block text-gray-700 mb-2" htmlFor="order">
                  Display Order
                </label>
                <input
                  type="number"
                  id="order"
                  name="order"
                  value={formData.order}
                  onChange={handleFormChange}
                  className="w-full p-2 border border-gray-300 rounded"
                  min="0"
                />
              </div>
              
              <div className="flex justify-end space-x-2">
                <button
                  type="button"
                  onClick={closeForm}
                  className="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                  {editingId ? 'Update' : 'Save'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Diet Types Table */}
      <div className="bg-white rounded-xl shadow-md border border-gray-100">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {dietTypesData.map((dietType) => (
                <tr key={dietType.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{dietType.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{dietType.name}</td>
                  <td className="px-6 py-4 text-gray-800">{dietType.description}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{dietType.order}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <button
                      onClick={() => openEditForm(dietType)}
                      className="text-blue-600 hover:text-blue-800"
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => handleDelete(dietType.id)}
                      className="text-red-600 hover:text-red-800 ml-3"
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
  );
} 