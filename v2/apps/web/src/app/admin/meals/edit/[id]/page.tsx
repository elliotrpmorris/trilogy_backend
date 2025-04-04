'use client';

import React, { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface Meal {
  id: string;
  name: string;
  description: string;
  calories: number;
  protein: number;
  carbs: number;
  fat: number;
  meal_type_id: string;
  meal_type_name: string;
  diet_type_id: string;
  diet_type_name: string;
  ingredients: string;
  instructions: string;
  prep_time: number;
  cook_time: number;
  servings: number;
  image_url: string;
  status: 'active' | 'inactive';
}

interface MealType {
  id: string;
  name: string;
}

interface DietType {
  id: string;
  name: string;
}

export default function EditMealPage() {
  const params = useParams();
  const router = useRouter();
  const mealId = params.id as string;
  
  const [formData, setFormData] = useState<Partial<Meal>>({});
  const [mealTypes, setMealTypes] = useState<MealType[]>([]);
  const [dietTypes, setDietTypes] = useState<DietType[]>([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  
  // Load meal data and filters on component mount
  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      setError('');
      
      try {
        const [mealResponse, mealTypesResponse, dietTypesResponse] = await Promise.all([
          convexAdapter.meals.getById(mealId),
          convexAdapter.meals.getMealTypes(),
          convexAdapter.meals.getDietTypes()
        ]);
        
        if (mealResponse.success) {
          setFormData(mealResponse.data || {});
        } else {
          setError(mealResponse.message || 'Failed to load meal data');
        }
        
        if (mealTypesResponse.success) {
          setMealTypes(mealTypesResponse.data || []);
        }
        
        if (dietTypesResponse.success) {
          setDietTypes(dietTypesResponse.data || []);
        }
      } catch (err) {
        console.error('Error fetching data:', err);
        setError('An error occurred while loading data');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [mealId]);

  // Handle input changes
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value, type } = e.target;
    
    // Convert numeric values to numbers
    if (type === 'number') {
      setFormData(prev => ({
        ...prev,
        [name]: value === '' ? '' : Number(value)
      }));
    } else {
      setFormData(prev => ({
        ...prev,
        [name]: value
      }));
    }
  };

  // Handle form submission
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    setError('');
    
    try {
      const response = await convexAdapter.meals.update(mealId, formData);
      
      if (response.success) {
        router.push(`/admin/meals/${mealId}`);
      } else {
        setError(response.message || 'Failed to update meal');
        setSaving(false);
      }
    } catch (err) {
      console.error('Error updating meal:', err);
      setError('An error occurred while updating the meal');
      setSaving(false);
    }
  };

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderMealTypes: MealType[] = [
    { id: "1", name: 'Breakfast' },
    { id: "2", name: 'Lunch' },
    { id: "3", name: 'Dinner' },
    { id: "4", name: 'Snack' },
  ];

  const placeholderDietTypes: DietType[] = [
    { id: "1", name: 'Regular' },
    { id: "2", name: 'Vegetarian' },
    { id: "3", name: 'Vegan' },
    { id: "4", name: 'Gluten-Free' },
    { id: "5", name: 'Keto' },
  ];

  // Use placeholder data if API data is not available
  const mealTypesData = mealTypes.length > 0 ? mealTypes : placeholderMealTypes;
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
        <h1 className="text-2xl font-bold">Edit Meal</h1>
        <button
          onClick={() => router.push(`/admin/meals/${mealId}`)}
          className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Cancel
        </button>
      </div>
      
      {error && (
        <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
          {error}
        </div>
      )}
      
      <div className="bg-white shadow rounded-lg p-6">
        <form onSubmit={handleSubmit}>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Basic Information */}
            <div className="col-span-2">
              <h2 className="text-lg font-medium border-b pb-2 mb-4">Basic Information</h2>
            </div>
            
            <div className="col-span-2">
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">
                Meal Name*
              </label>
              <input
                type="text"
                id="name"
                name="name"
                required
                value={formData.name || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div className="col-span-2">
              <label htmlFor="description" className="block text-sm font-medium text-gray-700 mb-1">
                Description
              </label>
              <textarea
                id="description"
                name="description"
                rows={3}
                value={formData.description || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>
            
            <div>
              <label htmlFor="meal_type_id" className="block text-sm font-medium text-gray-700 mb-1">
                Meal Type*
              </label>
              <select
                id="meal_type_id"
                name="meal_type_id"
                required
                value={formData.meal_type_id || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Select Meal Type</option>
                {mealTypesData.map((type) => (
                  <option key={type.id} value={type.id}>{type.name}</option>
                ))}
              </select>
            </div>
            
            <div>
              <label htmlFor="diet_type_id" className="block text-sm font-medium text-gray-700 mb-1">
                Diet Type*
              </label>
              <select
                id="diet_type_id"
                name="diet_type_id"
                required
                value={formData.diet_type_id || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Select Diet Type</option>
                {dietTypesData.map((type) => (
                  <option key={type.id} value={type.id}>{type.name}</option>
                ))}
              </select>
            </div>
            
            <div>
              <label htmlFor="image_url" className="block text-sm font-medium text-gray-700 mb-1">
                Image URL
              </label>
              <input
                type="text"
                id="image_url"
                name="image_url"
                value={formData.image_url || ''}
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
                value={formData.status || 'active'}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            
            {/* Nutritional Information */}
            <div className="col-span-2 mt-4">
              <h2 className="text-lg font-medium border-b pb-2 mb-4">Nutritional Information</h2>
            </div>
            
            <div>
              <label htmlFor="calories" className="block text-sm font-medium text-gray-700 mb-1">
                Calories*
              </label>
              <input
                type="number"
                id="calories"
                name="calories"
                required
                min="0"
                value={formData.calories || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label htmlFor="protein" className="block text-sm font-medium text-gray-700 mb-1">
                Protein (g)*
              </label>
              <input
                type="number"
                id="protein"
                name="protein"
                required
                min="0"
                step="0.1"
                value={formData.protein || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label htmlFor="carbs" className="block text-sm font-medium text-gray-700 mb-1">
                Carbs (g)*
              </label>
              <input
                type="number"
                id="carbs"
                name="carbs"
                required
                min="0"
                step="0.1"
                value={formData.carbs || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label htmlFor="fat" className="block text-sm font-medium text-gray-700 mb-1">
                Fat (g)*
              </label>
              <input
                type="number"
                id="fat"
                name="fat"
                required
                min="0"
                step="0.1"
                value={formData.fat || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            {/* Preparation Information */}
            <div className="col-span-2 mt-4">
              <h2 className="text-lg font-medium border-b pb-2 mb-4">Preparation Information</h2>
            </div>
            
            <div>
              <label htmlFor="prep_time" className="block text-sm font-medium text-gray-700 mb-1">
                Prep Time (minutes)
              </label>
              <input
                type="number"
                id="prep_time"
                name="prep_time"
                min="0"
                value={formData.prep_time || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label htmlFor="cook_time" className="block text-sm font-medium text-gray-700 mb-1">
                Cook Time (minutes)
              </label>
              <input
                type="number"
                id="cook_time"
                name="cook_time"
                min="0"
                value={formData.cook_time || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <div>
              <label htmlFor="servings" className="block text-sm font-medium text-gray-700 mb-1">
                Servings
              </label>
              <input
                type="number"
                id="servings"
                name="servings"
                min="1"
                value={formData.servings || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            {/* Recipe Information */}
            <div className="col-span-2 mt-4">
              <h2 className="text-lg font-medium border-b pb-2 mb-4">Recipe Information</h2>
            </div>
            
            <div className="col-span-2">
              <label htmlFor="ingredients" className="block text-sm font-medium text-gray-700 mb-1">
                Ingredients
              </label>
              <textarea
                id="ingredients"
                name="ingredients"
                rows={6}
                value={formData.ingredients || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="List ingredients one per line"
              ></textarea>
              <p className="text-xs text-gray-500 mt-1">Enter each ingredient on a new line</p>
            </div>
            
            <div className="col-span-2">
              <label htmlFor="instructions" className="block text-sm font-medium text-gray-700 mb-1">
                Instructions
              </label>
              <textarea
                id="instructions"
                name="instructions"
                rows={8}
                value={formData.instructions || ''}
                onChange={handleInputChange}
                className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Step 1: Preheat oven to 375°F..."
              ></textarea>
              <p className="text-xs text-gray-500 mt-1">Enter preparation steps in order</p>
            </div>
          </div>

          {/* Form Actions */}
          <div className="mt-8 flex justify-end">
            <button
              type="button"
              onClick={() => router.push(`/admin/meals/${mealId}`)}
              className="mr-4 px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={saving}
              className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
            >
              {saving ? 'Saving...' : 'Save Changes'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
} 