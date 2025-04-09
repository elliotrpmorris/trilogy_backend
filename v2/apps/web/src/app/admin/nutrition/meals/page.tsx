'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { useRouter, useSearchParams } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface Meal {
  id: string;
  name: string;
  mealTypeId: string;
  meal_type_name: string;
  image?: string;
  calories?: number;
}

interface MealNutrition {
  id: string;
  mealId: string;
  nutritionTypeId: string;
  nutritionName: string;
  nutritionUnit: string;
  value: number;
}

interface NutritionType {
  id: string;
  name: string;
  unit: string;
  description?: string;
}

export default function MealNutritionPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  
  const [meals, setMeals] = useState<Meal[]>([]);
  const [currentMeal, setCurrentMeal] = useState<Meal | null>(null);
  const [nutritionTypes, setNutritionTypes] = useState<NutritionType[]>([]);
  const [mealNutrition, setMealNutrition] = useState<MealNutrition[]>([]);
  
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  
  // Form state
  const [formData, setFormData] = useState<{[key: string]: number}>({});
  
  useEffect(() => {
    const fetchInitialData = async () => {
      setLoading(true);
      try {
        // Fetch meals and nutrition types in parallel
        const [mealsResponse, nutritionTypesResponse] = await Promise.all([
          convexAdapter.meals.getAll({ per_page: 100 }),
          // When API is ready, uncomment this code
          /*
          convexAdapter.meals.getNutritionTypes()
          */
          { success: 1, data: [] } // Placeholder
        ]);
        
        if (mealsResponse.success) {
          setMeals(mealsResponse.data.meals || []);
        } else {
          setError('Failed to load meals');
        }
        
        // When the API is ready, use this:
        /*
        if (nutritionTypesResponse.success) {
          setNutritionTypes(nutritionTypesResponse.data);
        } else {
          setError('Failed to load nutrition types');
        }
        */
        
        // For now, use placeholder data
        setNutritionTypes(placeholderNutritionTypes);
        
        // Check if a meal ID is specified in the URL
        const mealId = searchParams.get('mealId');
        if (mealId) {
          await fetchMealNutrition(mealId);
        }
      } catch (err) {
        console.error('Error fetching initial data:', err);
        setError('An error occurred while loading initial data');
      } finally {
        setLoading(false);
      }
    };
    
    fetchInitialData();
  }, [searchParams]);
  
  const fetchMealNutrition = async (mealId: string) => {
    setLoading(true);
    setError('');
    setSuccessMessage('');
    setCurrentMeal(null);
    setMealNutrition([]);
    setFormData({});
    
    try {
      // Fetch the meal details
      const mealResponse = await convexAdapter.meals.getById(mealId);
      
      if (mealResponse.success) {
        setCurrentMeal(mealResponse.data);
        
        // When the API is ready, use this:
        /*
        // Get the meal's nutrition information
        const nutritionResponse = await convexAdapter.meals.getMealNutrition(mealId);
        
        if (nutritionResponse.success) {
          setMealNutrition(nutritionResponse.data);
          
          // Initialize form data with existing values
          const initialFormData: {[key: string]: number} = {};
          nutritionResponse.data.forEach((item: MealNutrition) => {
            initialFormData[item.nutritionTypeId] = item.value;
          });
          setFormData(initialFormData);
        } else {
          setError('Failed to load meal nutrition information');
        }
        */
        
        // For now, use placeholder data based on the meal
        const mealIndex = parseInt(mealId.split('_').pop() || '0') % placeholderMealNutrition.length;
        const nutrition = placeholderMealNutrition[mealIndex].map(item => ({
          ...item,
          mealId
        }));
        
        setMealNutrition(nutrition);
        
        // Initialize form data with existing values
        const initialFormData: {[key: string]: number} = {};
        nutrition.forEach((item) => {
          initialFormData[item.nutritionTypeId] = item.value;
        });
        setFormData(initialFormData);
      } else {
        setError('Failed to load meal details');
      }
    } catch (err) {
      console.error('Error fetching meal nutrition:', err);
      setError('An error occurred while loading meal nutrition data');
    } finally {
      setLoading(false);
    }
  };
  
  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>, nutritionTypeId: string) => {
    const value = parseFloat(e.target.value) || 0;
    setFormData(prev => ({
      ...prev,
      [nutritionTypeId]: value
    }));
  };
  
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!currentMeal) {
      setError('No meal selected');
      return;
    }
    
    setSaving(true);
    setError('');
    setSuccessMessage('');
    
    try {
      // When the API is ready, use this:
      /*
      const nutritionData = Object.entries(formData).map(([nutritionTypeId, value]) => ({
        nutritionTypeId,
        value
      }));
      
      const response = await convexAdapter.meals.updateMealNutrition(currentMeal.id, nutritionData);
      
      if (response.success) {
        setSuccessMessage('Nutrition information updated successfully');
        
        // Refetch the nutrition data to get the updated values
        await fetchMealNutrition(currentMeal.id);
      } else {
        setError(response.message || 'Failed to update nutrition information');
      }
      */
      
      // For now, just simulate success
      setTimeout(() => {
        setSuccessMessage('Nutrition information updated successfully');
        
        // Update local state to reflect changes
        const updatedNutrition = mealNutrition.map(item => ({
          ...item,
          value: formData[item.nutritionTypeId] || item.value
        }));
        setMealNutrition(updatedNutrition);
        
        setSaving(false);
      }, 1000);
    } catch (err) {
      console.error('Error updating meal nutrition:', err);
      setError('An error occurred while updating nutrition information');
      setSaving(false);
    }
  };
  
  // Placeholder data for development
  const placeholderNutritionTypes: NutritionType[] = [
    { id: "1", name: 'Calories', unit: 'kcal', description: 'Energy content of food' },
    { id: "2", name: 'Protein', unit: 'g', description: 'Amino acids for muscle building' },
    { id: "3", name: 'Carbohydrates', unit: 'g', description: 'Primary energy source' },
    { id: "4", name: 'Fat', unit: 'g', description: 'Stored energy and insulation' },
    { id: "5", name: 'Fiber', unit: 'g', description: 'Indigestible plant material' },
    { id: "6", name: 'Sugar', unit: 'g', description: 'Simple carbohydrates' },
    { id: "7", name: 'Sodium', unit: 'mg', description: 'Salt content' },
    { id: "8", name: 'Vitamin C', unit: 'mg', description: 'Antioxidant nutrient' },
  ];
  
  const placeholderMealNutrition = [
    [
      { id: "n1", nutritionTypeId: "1", nutritionName: "Calories", nutritionUnit: "kcal", value: 320 },
      { id: "n2", nutritionTypeId: "2", nutritionName: "Protein", nutritionUnit: "g", value: 15 },
      { id: "n3", nutritionTypeId: "3", nutritionName: "Carbohydrates", nutritionUnit: "g", value: 40 },
      { id: "n4", nutritionTypeId: "4", nutritionName: "Fat", nutritionUnit: "g", value: 12 },
    ],
    [
      { id: "n1", nutritionTypeId: "1", nutritionName: "Calories", nutritionUnit: "kcal", value: 450 },
      { id: "n2", nutritionTypeId: "2", nutritionName: "Protein", nutritionUnit: "g", value: 30 },
      { id: "n3", nutritionTypeId: "3", nutritionName: "Carbohydrates", nutritionUnit: "g", value: 25 },
      { id: "n4", nutritionTypeId: "4", nutritionName: "Fat", nutritionUnit: "g", value: 22 },
      { id: "n5", nutritionTypeId: "5", nutritionName: "Fiber", nutritionUnit: "g", value: 6 },
    ],
    [
      { id: "n1", nutritionTypeId: "1", nutritionName: "Calories", nutritionUnit: "kcal", value: 220 },
      { id: "n2", nutritionTypeId: "2", nutritionName: "Protein", nutritionUnit: "g", value: 8 },
      { id: "n3", nutritionTypeId: "3", nutritionName: "Carbohydrates", nutritionUnit: "g", value: 18 },
      { id: "n4", nutritionTypeId: "4", nutritionName: "Fat", nutritionUnit: "g", value: 14 },
      { id: "n7", nutritionTypeId: "7", nutritionName: "Sodium", nutritionUnit: "mg", value: 250 },
    ],
  ];
  
  // Use placeholder data if API data is not available
  const mealsData = meals.length > 0 ? meals : [
    { id: "meal_1", name: "Grilled Chicken Salad", mealTypeId: "1", meal_type_name: "Lunch", calories: 320 },
    { id: "meal_2", name: "Protein Breakfast Bowl", mealTypeId: "2", meal_type_name: "Breakfast", calories: 450 },
    { id: "meal_3", name: "Vegetable Stir Fry", mealTypeId: "3", meal_type_name: "Dinner", calories: 280 },
    { id: "meal_4", name: "Omelette with Avocado", mealTypeId: "2", meal_type_name: "Breakfast", calories: 350 },
    { id: "meal_5", name: "Salmon with Quinoa", mealTypeId: "3", meal_type_name: "Dinner", calories: 420 },
  ];
  
  if (loading && !currentMeal) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    );
  }
  
  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold">Meal Nutrition Management</h1>
        <Link
          href="/admin/nutrition"
          className="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
        >
          Nutrition Types
        </Link>
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
      
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {/* Meal List Sidebar */}
        <div className="md:col-span-1 bg-white p-4 rounded-lg shadow">
          <h2 className="text-lg font-semibold mb-4">Select a Meal</h2>
          <div className="space-y-2 max-h-[70vh] overflow-y-auto">
            {mealsData.map((meal) => (
              <button
                key={meal.id}
                onClick={() => fetchMealNutrition(meal.id)}
                className={`block w-full text-left p-3 rounded transition ${
                  currentMeal?.id === meal.id
                    ? 'bg-blue-100 text-blue-700'
                    : 'hover:bg-gray-100'
                }`}
              >
                <div className="font-medium">{meal.name}</div>
                <div className="text-sm text-gray-500">{meal.meal_type_name}</div>
                {meal.calories && (
                  <div className="text-xs mt-1">
                    <span className="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">
                      {meal.calories} kcal
                    </span>
                  </div>
                )}
              </button>
            ))}
          </div>
        </div>
        
        {/* Nutrition Form */}
        <div className="md:col-span-3 bg-white p-6 rounded-lg shadow">
          {currentMeal ? (
            <>
              <div className="mb-6">
                <h2 className="text-xl font-semibold">
                  Nutritional Information for {currentMeal.name}
                </h2>
                <p className="text-gray-500 text-sm mt-1">
                  {currentMeal.meal_type_name}
                </p>
              </div>
              
              {loading ? (
                <div className="flex justify-center items-center h-40">
                  <div className="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                </div>
              ) : (
                <form onSubmit={handleSubmit}>
                  <div className="space-y-4">
                    {nutritionTypes.map((nutrition) => (
                      <div key={nutrition.id} className="flex items-center space-x-3">
                        <div className="w-1/3">
                          <label 
                            htmlFor={`nutrition-${nutrition.id}`}
                            className="font-medium text-gray-700"
                          >
                            {nutrition.name}
                          </label>
                        </div>
                        <div className="w-1/3">
                          <input
                            type="number"
                            id={`nutrition-${nutrition.id}`}
                            value={formData[nutrition.id] || 0}
                            onChange={(e) => handleInputChange(e, nutrition.id)}
                            step="0.1"
                            min="0"
                            className="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                          />
                        </div>
                        <div className="w-1/3 text-gray-500 text-sm">
                          {nutrition.unit}
                        </div>
                      </div>
                    ))}
                  </div>
                  
                  <div className="mt-6 flex justify-end">
                    <button
                      type="submit"
                      className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-blue-300 disabled:cursor-not-allowed"
                      disabled={saving}
                    >
                      {saving ? 'Saving...' : 'Save Nutrition Information'}
                    </button>
                  </div>
                </form>
              )}
            </>
          ) : (
            <div className="text-center py-12">
              <div className="text-gray-400 mb-4">
                <svg className="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <h3 className="text-lg font-medium text-gray-500">No Meal Selected</h3>
              <p className="text-gray-400 mt-1">
                Select a meal from the list to manage its nutritional information
              </p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
} 