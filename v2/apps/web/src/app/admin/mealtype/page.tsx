'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface MealType {
  id: string;
  name: string;
  description: string;
  order: number;
  createdAt: number;
  updatedAt: number;
}

export default function MealTypesPage() {
  const router = useRouter();
  
  const [mealTypes, setMealTypes] = useState<MealType[]>([]);
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

  // Fetch meal types on component mount
  useEffect(() => {
    fetchMealTypes();
  }, []);

  const fetchMealTypes = async () => {
    setLoading(true);
    setError('');
    
    try {
      const response = await convexAdapter.meals.getTypes();
      
      if (response.success) {
        setMealTypes(response.data);
      } else {
        setError(response.message || 'Failed to load meal types');
      }
    } catch (err) {
      console.error('Meal types fetch error:', err);
      setError('An error occurred while loading meal types');
    } finally {
      setLoading(false);
    }
  };

  const openCreateForm = () => {
    setFormData({
      name: '',
      description: '',
      order: mealTypes.length + 1
    });
    setEditingId(null);
    setIsFormOpen(true);
  };

  const openEditForm = (mealType: MealType) => {
    setFormData({
      name: mealType.name,
      description: mealType.description || '',
      order: mealType.order || 0
    });
    setEditingId(mealType.id);
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
    
    try {
      let response;
      
      if (editingId) {
        // Update existing meal type
        response = await convexAdapter.meals.updateType(editingId, formData);
      } else {
        // Create new meal type
        response = await convexAdapter.meals.createType(formData);
      }
      
      if (response.success) {
        fetchMealTypes();
        closeForm();
      } else {
        setError(response.message || 'Operation failed');
      }
    } catch (err) {
      console.error('Form submission error:', err);
      setError('An error occurred while processing your request');
    }
  };

  const handleDelete = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this meal type? This will affect all associated meals.')) {
      try {
        const response = await convexAdapter.meals.deleteType(id);
        
        if (response.success) {
          fetchMealTypes();
        } else {
          setError(response.message || 'Failed to delete meal type');
        }
      } catch (err) {
        console.error('Error deleting meal type:', err);
        setError('An error occurred while deleting the meal type');
      }
    }
  };

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderMealTypes: MealType[] = [
    { id: "1", name: 'Breakfast', description: 'Morning meals', order: 1, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "2", name: 'Lunch', description: 'Midday meals', order: 2, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "3", name: 'Dinner', description: 'Evening meals', order: 3, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "4", name: 'Snack', description: 'Between-meal snacks', order: 4, createdAt: Date.now(), updatedAt: Date.now() },
  ];

  // Use placeholder data if API data is not available
  const mealTypesData = mealTypes.length > 0 ? mealTypes : placeholderMealTypes;


  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Meal Types</h1>
        <button
          onClick={openCreateForm}
          className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Add Meal Type
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
              {editingId ? 'Edit Meal Type' : 'Add Meal Type'}
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

      {/* Meal Types Table */}
      <div className="bg-white rounded-xl shadow-md border border-gray-100">
        <div className="p-6 border-b">
          <h2 className="text-xl font-semibold text-gray-800">All Meal Types</h2>
        </div>
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
              {mealTypesData.map((mealType) => (
                <tr key={mealType.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{mealType.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{mealType.name}</td>
                  <td className="px-6 py-4 text-gray-800">{mealType.description || '-'}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{mealType.order || '-'}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <button
                      onClick={() => openEditForm(mealType)}
                      className="text-blue-600 hover:text-blue-800"
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => handleDelete(mealType.id)}
                      className="text-red-600 hover:text-red-800"
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