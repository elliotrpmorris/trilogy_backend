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
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Nutrition Types</h1>
        <div className="space-x-3">
          <Link
            href="/admin/nutrition/meals"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Manage Meal Nutrition
          </Link>
          <button
            onClick={openCreateForm}
            className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            Add Nutrition Type
          </button>
        </div>
      </div>

      {error && (
        <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
          {error}
        </div>
      )}

      {successMessage && (
        <div className="bg-green-100 p-4 rounded text-green-700 mb-4">
          {successMessage}
        </div>
      )}

      {/* Form Modal */}
      {isFormOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 className="text-xl font-semibold mb-4">
              {editingId ? 'Edit Nutrition Type' : 'Add Nutrition Type'}
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
                <label className="block text-gray-700 mb-2" htmlFor="unit">
                  Unit <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="unit"
                  name="unit"
                  value={formData.unit}
                  onChange={handleFormChange}
                  className="w-full p-2 border border-gray-300 rounded"
                  placeholder="g, mg, kcal, etc."
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

      {/* Nutrition Types Table */}
      <div className="bg-white rounded-xl shadow-md border border-gray-100">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {nutritionTypes.map((nutritionType) => (
                <tr key={nutritionType.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{nutritionType.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{nutritionType.name}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{nutritionType.unit}</td>
                  <td className="px-6 py-4 text-gray-800">{nutritionType.description}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{nutritionType.order}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <button
                      onClick={() => openEditForm(nutritionType)}
                      className="text-blue-600 hover:text-blue-800"
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => handleDelete(nutritionType.id)}
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