'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';
import Link from 'next/link';

// Types
interface NutritionType {
  id: string;
  name: string;
  description: string;
  unit: string;
  order: number;
  createdAt: number;
  updatedAt: number;
}

export default function NutritionTypesPage() {
  const router = useRouter();
  
  const [nutritionTypes, setNutritionTypes] = useState<NutritionType[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  
  // Form state
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<string | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    description: '',
    unit: '',
    order: 0
  });

  // Fetch nutrition types on component mount
  useEffect(() => {
    fetchNutritionTypes();
  }, []);

  const fetchNutritionTypes = async () => {
    setLoading(true);
    setError('');
    
    try {
      // When the backend API endpoint is ready, uncomment this code
      /*
      const response = await convexAdapter.meals.getNutritionTypes();
      
      if (response.success) {
        setNutritionTypes(response.data);
      } else {
        setError(response.message || 'Failed to load nutrition types');
      }
      */
      
      // For now, simulate API call with placeholder data
      setTimeout(() => {
        setNutritionTypes(placeholderNutritionTypes);
        setLoading(false);
      }, 500);
    } catch (err) {
      console.error('Nutrition types fetch error:', err);
      setError('An error occurred while loading nutrition types');
      setLoading(false);
    }
  };

  const openCreateForm = () => {
    setError('');
    setSuccessMessage('');
    setFormData({
      name: '',
      description: '',
      unit: '',
      order: nutritionTypes.length + 1
    });
    setEditingId(null);
    setIsFormOpen(true);
  };

  const openEditForm = (nutritionType: NutritionType) => {
    setError('');
    setSuccessMessage('');
    setFormData({
      name: nutritionType.name,
      description: nutritionType.description || '',
      unit: nutritionType.unit || '',
      order: nutritionType.order || 0
    });
    setEditingId(nutritionType.id);
    setIsFormOpen(true);
  };

  const closeForm = () => {
    setIsFormOpen(false);
    setEditingId(null);
    setFormData({
      name: '',
      description: '',
      unit: '',
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
    
    if (!formData.unit.trim()) {
      setError('Unit is required');
      return;
    }
    
    // When the backend API endpoint is ready, uncomment this code
    /*
    try {
      let response;
      
      if (editingId) {
        // Update existing nutrition type
        response = await convexAdapter.meals.updateNutritionType(editingId, formData);
      } else {
        // Create new nutrition type
        response = await convexAdapter.meals.createNutritionType(formData);
      }
      
      if (response.success) {
        setSuccessMessage(editingId ? 'Nutrition type updated successfully' : 'Nutrition type created successfully');
        fetchNutritionTypes();
        closeForm();
      } else {
        setError(response.message || 'Operation failed');
      }
    } catch (err) {
      console.error('Form submission error:', err);
      setError('An error occurred while processing your request');
    }
    */
    
    // For now, simulate success response
    setSuccessMessage(editingId ? 'Nutrition type updated successfully' : 'Nutrition type created successfully');
    
    // If editing, update the item in the placeholder data
    if (editingId) {
      const updatedNutritionTypes = nutritionTypes.map(item => 
        item.id === editingId ? { ...item, ...formData, updatedAt: Date.now() } : item
      );
      setNutritionTypes(updatedNutritionTypes);
    } else {
      // If creating, add a new item
      const newItem = {
        ...formData,
        id: `temp_${Date.now()}`,
        createdAt: Date.now(),
        updatedAt: Date.now()
      };
      setNutritionTypes([...nutritionTypes, newItem]);
    }
    
    closeForm();
  };

  const handleDelete = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this nutrition type? This may affect associated meals.')) {
      // When the backend API endpoint is ready, uncomment this code
      /*
      try {
        const response = await convexAdapter.meals.deleteNutritionType(id);
        
        if (response.success) {
          setSuccessMessage('Nutrition type deleted successfully');
          fetchNutritionTypes();
        } else {
          setError(response.message || 'Failed to delete nutrition type');
        }
      } catch (err) {
        console.error('Error deleting nutrition type:', err);
        setError('An error occurred while deleting the nutrition type');
      }
      */
      
      // For now, simulate successful deletion
      setSuccessMessage('Nutrition type deleted successfully');
      const filteredNutritionTypes = nutritionTypes.filter(item => item.id !== id);
      setNutritionTypes(filteredNutritionTypes);
    }
  };

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderNutritionTypes: NutritionType[] = [
    { id: "1", name: 'Calories', description: 'Energy content of food', unit: 'kcal', order: 1, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "2", name: 'Protein', description: 'Amino acids for muscle building', unit: 'g', order: 2, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "3", name: 'Carbohydrates', description: 'Primary energy source', unit: 'g', order: 3, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "4", name: 'Fat', description: 'Stored energy and insulation', unit: 'g', order: 4, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "5", name: 'Fiber', description: 'Indigestible plant material', unit: 'g', order: 5, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "6", name: 'Sugar', description: 'Simple carbohydrates', unit: 'g', order: 6, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "7", name: 'Sodium', description: 'Salt content', unit: 'mg', order: 7, createdAt: Date.now(), updatedAt: Date.now() },
    { id: "8", name: 'Vitamin C', description: 'Antioxidant nutrient', unit: 'mg', order: 8, createdAt: Date.now(), updatedAt: Date.now() },
  ];

 
  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Nutrition Types</h1>
            <p className="mt-2 text-sm text-gray-500">Manage your nutrition categories and their display order</p>
          </div>
          <div className="space-x-3">
            <Link
              href="/admin/nutrition/meals"
              className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fillRule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clipRule="evenodd" />
              </svg>
              Manage Meal Nutrition
            </Link>
            <button
              onClick={openCreateForm}
              className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clipRule="evenodd" />
              </svg>
              Add Nutrition Type
            </button>
          </div>
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

        {successMessage && (
          <div className="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
            <div className="flex">
              <div className="flex-shrink-0">
                <svg className="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                </svg>
              </div>
              <div className="ml-3">
                <p className="text-sm text-green-700">{successMessage}</p>
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
                  {editingId ? 'Edit Nutrition Type' : 'Add Nutrition Type'}
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
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="unit">
                      Unit <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="unit"
                      name="unit"
                      value={formData.unit}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="g, mg, kcal, etc."
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

        {/* Nutrition Types Table */}
        <div className="bg-white shadow rounded-lg overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-200">
            <h2 className="text-lg font-medium text-gray-900">All Nutrition Types</h2>
          </div>
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {nutritionTypes.map((nutritionType) => (
                  <tr key={nutritionType.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">{nutritionType.name}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500">{nutritionType.unit}</div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="text-sm text-gray-500">{nutritionType.description || '-'}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500">{nutritionType.order || '-'}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                      <button
                        onClick={() => openEditForm(nutritionType)}
                        className="text-indigo-600 hover:text-indigo-900"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => handleDelete(nutritionType.id)}
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