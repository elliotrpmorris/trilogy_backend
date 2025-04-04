'use client';

import React, { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
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
  created_at: string;
  updated_at: string;
}

export default function MealDetailPage() {
  const params = useParams();
  const router = useRouter();
  const mealId = params.id as string;
  
  const [meal, setMeal] = useState<Meal | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [activeTab, setActiveTab] = useState('details');
  
  useEffect(() => {
    const fetchMealData = async () => {
      setLoading(true);
      setError('');
      
      try {
        // Get meal data from Convex via adapter
        const response = await convexAdapter.meals.getById(mealId);
        
        if (response.success) {
          setMeal(response.data);
        } else {
          setError(response.message || 'Failed to load meal data');
        }
      } catch (err) {
        console.error('Meal fetch error:', err);
        setError('An error occurred while loading meal data');
      } finally {
        setLoading(false);
      }
    };

    if (mealId) {
      fetchMealData();
    }
  }, [mealId]);

  // Handle meal deletion
  const handleDeleteMeal = async () => {
    if (window.confirm('Are you sure you want to delete this meal?')) {
      try {
        const response = await convexAdapter.meals.delete(mealId);
        
        if (response.success) {
          router.push('/admin/meals');
        } else {
          setError(response.message || 'Failed to delete meal');
        }
      } catch (err) {
        console.error('Error deleting meal:', err);
        setError('An error occurred while deleting the meal');
      }
    }
  };

  // Handle meal duplication
  const handleDuplicateMeal = async () => {
    try {
      const response = await convexAdapter.meals.duplicate(mealId);
      
      if (response.success) {
        router.push(`/admin/meals/${response.data.id}`);
      } else {
        setError(response.message || 'Failed to duplicate meal');
      }
    } catch (err) {
      console.error('Error duplicating meal:', err);
      setError('An error occurred while duplicating the meal');
    }
  };

  // Placeholder meal data for development - will be removed when Convex is fully integrated
  const mockMeal: Meal = {
    id: mealId,
    name: "Grilled Salmon with Asparagus",
    description: "A healthy and delicious grilled salmon dish served with fresh asparagus.",
    calories: 420,
    protein: 32,
    carbs: 8,
    fat: 26,
    meal_type_id: "3",
    meal_type_name: "Dinner",
    diet_type_id: "1",
    diet_type_name: "Regular",
    ingredients: "2 salmon fillets (6oz each)\n1 bunch asparagus\n2 tbsp olive oil\n2 cloves garlic, minced\n1 lemon\nSalt and pepper to taste",
    instructions: "1. Preheat grill to medium-high heat\n2. Trim asparagus and toss with 1 tbsp olive oil, salt, and pepper\n3. Season salmon with salt, pepper, and garlic\n4. Grill salmon for 4-5 minutes per side\n5. Grill asparagus for 3-4 minutes, turning occasionally\n6. Serve with lemon wedges",
    prep_time: 10,
    cook_time: 15,
    servings: 2,
    image_url: "https://images.unsplash.com/photo-1559847844-5315695dadae",
    status: "active",
    created_at: "2023-05-15",
    updated_at: "2023-05-15"
  };

  // Use mock data if API data is not available
  const mealData = meal || mockMeal;

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
          onClick={() => router.push('/admin/meals')}
          className="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
        >
          Back to Meals
        </button>
      </div>
    );
  }

  return (
    <div>
      {/* Header with meal info and actions */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
          <h1 className="text-3xl font-bold">{mealData.name}</h1>
          <p className="text-gray-500">
            {mealData.meal_type_name} · {mealData.diet_type_name} · {mealData.calories} calories
          </p>
        </div>
        <div className="flex flex-wrap gap-2">
          <Link href={`/admin/meals/edit/${mealData.id}`} className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Edit Meal
          </Link>
          <button 
            onClick={handleDuplicateMeal}
            className="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
          >
            Duplicate
          </button>
          <button 
            onClick={handleDeleteMeal}
            className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          >
            Delete
          </button>
          <button 
            onClick={() => router.push('/admin/meals')}
            className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
          >
            Back to List
          </button>
        </div>
      </div>

      {/* Meal image */}
      <div className="relative h-64 md:h-80 mb-6 rounded-lg overflow-hidden shadow-md">
        <img 
          src={mealData.image_url || "https://via.placeholder.com/800x400?text=No+Image"} 
          alt={mealData.name} 
          className="w-full h-full object-cover"
        />
        <div className="absolute top-4 right-4">
          <span className={`px-3 py-1 text-sm font-medium rounded-full ${
            mealData.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          }`}>
            {mealData.status === 'active' ? 'Active' : 'Inactive'}
          </span>
        </div>
      </div>

      {/* Tabs */}
      <div className="border-b mb-6">
        <nav className="flex -mb-px">
          <button
            onClick={() => setActiveTab('details')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'details'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Details
          </button>
          <button
            onClick={() => setActiveTab('nutrition')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'nutrition'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Nutrition
          </button>
          <button
            onClick={() => setActiveTab('recipe')}
            className={`px-6 py-3 border-b-2 font-medium text-sm ${
              activeTab === 'recipe'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            }`}
          >
            Recipe
          </button>
        </nav>
      </div>

      {/* Tab Content */}
      <div className="bg-white shadow rounded-lg p-6">
        {/* Details Tab */}
        {activeTab === 'details' && (
          <div className="space-y-6">
            <div>
              <h2 className="text-xl font-semibold mb-2">Description</h2>
              <p className="text-gray-700">
                {mealData.description}
              </p>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="bg-gray-50 p-4 rounded">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Prep Time</h3>
                <p className="text-lg">{mealData.prep_time} minutes</p>
              </div>
              <div className="bg-gray-50 p-4 rounded">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Cook Time</h3>
                <p className="text-lg">{mealData.cook_time} minutes</p>
              </div>
              <div className="bg-gray-50 p-4 rounded">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Servings</h3>
                <p className="text-lg">{mealData.servings}</p>
              </div>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 className="text-lg font-medium mb-2">Meal Type</h3>
                <p className="bg-blue-100 inline-block px-3 py-1 rounded-full text-blue-800">
                  {mealData.meal_type_name}
                </p>
              </div>
              <div>
                <h3 className="text-lg font-medium mb-2">Diet Type</h3>
                <p className="bg-purple-100 inline-block px-3 py-1 rounded-full text-purple-800">
                  {mealData.diet_type_name}
                </p>
              </div>
            </div>
          </div>
        )}

        {/* Nutrition Tab */}
        {activeTab === 'nutrition' && (
          <div className="space-y-6">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div className="bg-blue-50 p-4 rounded text-center">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Calories</h3>
                <p className="text-2xl font-semibold">{mealData.calories}</p>
                <p className="text-xs text-gray-500">kcal</p>
              </div>
              <div className="bg-red-50 p-4 rounded text-center">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Protein</h3>
                <p className="text-2xl font-semibold">{mealData.protein}</p>
                <p className="text-xs text-gray-500">grams</p>
              </div>
              <div className="bg-yellow-50 p-4 rounded text-center">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Carbs</h3>
                <p className="text-2xl font-semibold">{mealData.carbs}</p>
                <p className="text-xs text-gray-500">grams</p>
              </div>
              <div className="bg-green-50 p-4 rounded text-center">
                <h3 className="text-sm font-medium text-gray-500 mb-1">Fat</h3>
                <p className="text-2xl font-semibold">{mealData.fat}</p>
                <p className="text-xs text-gray-500">grams</p>
              </div>
            </div>
            
            <div>
              <h3 className="text-lg font-medium mb-2">Macronutrient Breakdown</h3>
              <div className="h-8 flex rounded-full overflow-hidden">
                <div 
                  className="bg-red-500" 
                  style={{width: `${(mealData.protein * 4 / mealData.calories) * 100}%`}}
                  title={`Protein: ${Math.round((mealData.protein * 4 / mealData.calories) * 100)}%`}
                ></div>
                <div 
                  className="bg-yellow-500" 
                  style={{width: `${(mealData.carbs * 4 / mealData.calories) * 100}%`}}
                  title={`Carbs: ${Math.round((mealData.carbs * 4 / mealData.calories) * 100)}%`}
                ></div>
                <div 
                  className="bg-green-500" 
                  style={{width: `${(mealData.fat * 9 / mealData.calories) * 100}%`}}
                  title={`Fat: ${Math.round((mealData.fat * 9 / mealData.calories) * 100)}%`}
                ></div>
              </div>
              <div className="flex justify-between text-xs mt-2">
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-red-500 mr-1 rounded-full"></div>
                  <span>Protein ({Math.round((mealData.protein * 4 / mealData.calories) * 100)}%)</span>
                </div>
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-yellow-500 mr-1 rounded-full"></div>
                  <span>Carbs ({Math.round((mealData.carbs * 4 / mealData.calories) * 100)}%)</span>
                </div>
                <div className="flex items-center">
                  <div className="w-3 h-3 bg-green-500 mr-1 rounded-full"></div>
                  <span>Fat ({Math.round((mealData.fat * 9 / mealData.calories) * 100)}%)</span>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Recipe Tab */}
        {activeTab === 'recipe' && (
          <div className="space-y-6">
            <div>
              <h2 className="text-xl font-semibold mb-3">Ingredients</h2>
              <div className="bg-gray-50 p-4 rounded">
                <pre className="whitespace-pre-wrap font-sans">
                  {mealData.ingredients}
                </pre>
              </div>
            </div>
            
            <div>
              <h2 className="text-xl font-semibold mb-3">Instructions</h2>
              <div className="bg-gray-50 p-4 rounded">
                <pre className="whitespace-pre-wrap font-sans">
                  {mealData.instructions}
                </pre>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Metadata Footer */}
      <div className="mt-6 text-sm text-gray-500 flex justify-between">
        <div>Created: {new Date(mealData.created_at).toLocaleDateString()}</div>
        <div>Last Updated: {new Date(mealData.updated_at).toLocaleDateString()}</div>
      </div>
    </div>
  );
} 