'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { useRouter, usePathname, useSearchParams } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';

// Types
interface Meal {
  id: string;
  name: string;
  calories: number;
  protein: number;
  carbs: number;
  fat: number;
  meal_type_id: string;
  meal_type_name: string;
  diet_type_id: string;
  diet_type_name: string;
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

export default function MealsPage() {
  const router = useRouter();
  const pathname = usePathname();
  const searchParams = useSearchParams();
  
  const [meals, setMeals] = useState<Meal[]>([]);
  const [mealTypes, setMealTypes] = useState<MealType[]>([]);
  const [dietTypes, setDietTypes] = useState<DietType[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  
  // Pagination state
  const [totalMeals, setTotalMeals] = useState(0);
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(10);
  
  // Search and filter state
  const [searchTerm, setSearchTerm] = useState('');
  const [filterMealType, setFilterMealType] = useState('');
  const [filterDietType, setFilterDietType] = useState('');

  // Update query parameters when filters change
  const updateQueryParams = (params: Record<string, string | undefined>) => {
    const newParams = new URLSearchParams(searchParams);
    
    // Update or add new parameters
    Object.entries(params).forEach(([key, value]) => {
      if (value) {
        newParams.set(key, value);
      } else {
        newParams.delete(key);
      }
    });
    
    router.push(`${pathname}?${newParams.toString()}`);
  };

  // Handle search form submission
  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    updateQueryParams({ search: searchTerm, page: '1' });
  };

  // Handle meal type filter change
  const handleMealTypeChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const value = e.target.value;
    setFilterMealType(value);
    updateQueryParams({ meal_type_id: value || undefined, page: '1' });
  };

  // Handle diet type filter change
  const handleDietTypeChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const value = e.target.value;
    setFilterDietType(value);
    updateQueryParams({ diet_type_id: value || undefined, page: '1' });
  };

  // Handle page change
  const handlePageChange = (newPage: number) => {
    setPage(newPage);
    updateQueryParams({ page: newPage.toString() });
  };

  // Fetch meals when filters or pagination changes
  useEffect(() => {
    const fetchMeals = async () => {
      setLoading(true);
      setError('');
      
      try {
        // Get query parameters
        const search = searchParams.get('search') || '';
        const mealTypeId = searchParams.get('meal_type_id') || '';
        const dietTypeId = searchParams.get('diet_type_id') || '';
        const currentPage = parseInt(searchParams.get('page') || '1', 10);
        
        // Update local state with URL parameters
        setSearchTerm(search);
        setFilterMealType(mealTypeId);
        setFilterDietType(dietTypeId);
        setPage(currentPage);
        
        // Prepare query parameters
        const queryParams: any = {
          page: currentPage,
          per_page: perPage,
          search: search
        };

        // Only add meal_type_id and diet_type_id if they have valid values
        if (mealTypeId && mealTypeId !== '') {
          queryParams.meal_type_id = mealTypeId;
        }
        if (dietTypeId && dietTypeId !== '') {
          queryParams.diet_type_id = dietTypeId;
        }
        
        // Fetch meals from Convex via adapter
        const response = await convexAdapter.meals.getAll(queryParams);
        
        if (response.success) {
          setMeals(response.data.meals);
          setTotalMeals(response.data.total);
        } else {
          setError(response.message || 'Failed to load meals');
        }
      } catch (err) {
        console.error('Meals fetch error:', err);
        setError('An error occurred while loading meals');
      } finally {
        setLoading(false);
      }
    };

    fetchMeals();
  }, [searchParams, perPage]);

  // Fetch meal types and diet types
  useEffect(() => {
    const fetchTypes = async () => {
      try {
        const [mealTypesResponse, dietTypesResponse] = await Promise.all([
          convexAdapter.meals.getTypes(),
          convexAdapter.meals.getDietTypes()
        ]);
        
        if (mealTypesResponse.success) {
          setMealTypes(mealTypesResponse.data);
        }
        
        if (dietTypesResponse.success) {
          setDietTypes(dietTypesResponse.data);
        }
      } catch (err) {
        console.error('Error fetching types:', err);
      }
    };

    fetchTypes();
  }, []);

  // Handle meal deletion
  const handleDeleteMeal = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this meal?')) {
      try {
        const response = await convexAdapter.meals.delete(id);
        
        if (response.success) {
          // Refresh the meals list
          router.refresh();
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
  const handleDuplicateMeal = async (id: string) => {
    try {
      const response = await convexAdapter.meals.duplicate(id);
      
      if (response.success) {
        // Refresh the meals list
        router.refresh();
        alert('Meal duplicated successfully');
      } else {
        setError(response.message || 'Failed to duplicate meal');
      }
    } catch (err) {
      console.error('Error duplicating meal:', err);
      setError('An error occurred while duplicating the meal');
    }
  };

  // Placeholder data for development - will be removed when Convex is fully integrated
  const placeholderMeals: Meal[] = [
    { id: "1", name: 'Classic Omelette', calories: 350, protein: 20, carbs: 5, fat: 25, meal_type_id: "1", meal_type_name: 'Breakfast', diet_type_id: "1", diet_type_name: 'Regular', image_url: 'https://images.unsplash.com/photo-1555403884-0b54712d1629', status: 'active' },
    { id: "2", name: 'Avocado Toast', calories: 280, protein: 8, carbs: 30, fat: 15, meal_type_id: "1", meal_type_name: 'Breakfast', diet_type_id: "2", diet_type_name: 'Vegetarian', image_url: 'https://images.unsplash.com/photo-1535227798054-e4373ef3795a', status: 'active' },
    { id: "3", name: 'Chicken Salad', calories: 320, protein: 25, carbs: 10, fat: 18, meal_type_id: "2", meal_type_name: 'Lunch', diet_type_id: "1", diet_type_name: 'Regular', image_url: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c', status: 'active' },
    { id: "4", name: 'Veggie Burger', calories: 380, protein: 15, carbs: 45, fat: 12, meal_type_id: "2", meal_type_name: 'Lunch', diet_type_id: "2", diet_type_name: 'Vegetarian', image_url: 'https://images.unsplash.com/photo-1520072959219-c595dc870360', status: 'active' },
    { id: "5", name: 'Salmon with Asparagus', calories: 420, protein: 32, carbs: 8, fat: 26, meal_type_id: "3", meal_type_name: 'Dinner', diet_type_id: "1", diet_type_name: 'Regular', image_url: 'https://images.unsplash.com/photo-1559847844-5315695dadae', status: 'active' },
    { id: "6", name: 'Lentil Soup', calories: 250, protein: 12, carbs: 30, fat: 8, meal_type_id: "3", meal_type_name: 'Dinner', diet_type_id: "3", diet_type_name: 'Vegan', image_url: 'https://images.unsplash.com/photo-1560684352-8497838a2229', status: 'active' },
    { id: "7", name: 'Greek Yogurt Parfait', calories: 220, protein: 15, carbs: 25, fat: 6, meal_type_id: "4", meal_type_name: 'Snack', diet_type_id: "2", diet_type_name: 'Vegetarian', image_url: 'https://images.unsplash.com/photo-1505253758473-96b7015fcd40', status: 'active' },
    { id: "8", name: 'Protein Smoothie', calories: 180, protein: 20, carbs: 15, fat: 3, meal_type_id: "4", meal_type_name: 'Snack', diet_type_id: "1", diet_type_name: 'Regular', image_url: 'https://images.unsplash.com/photo-1553530666-ba11a90a0197', status: 'active' },
    { id: "9", name: 'Stuffed Bell Peppers', calories: 310, protein: 18, carbs: 20, fat: 16, meal_type_id: "3", meal_type_name: 'Dinner', diet_type_id: "4", diet_type_name: 'Gluten-Free', image_url: 'https://images.unsplash.com/photo-1600335895229-6e75511892c8', status: 'inactive' },
    { id: "10", name: 'Chia Pudding', calories: 200, protein: 6, carbs: 22, fat: 10, meal_type_id: "1", meal_type_name: 'Breakfast', diet_type_id: "3", diet_type_name: 'Vegan', image_url: 'https://images.unsplash.com/photo-1568051243851-f9b136bbe169', status: 'active' },
  ];

  // Placeholder meal types
  const placeholderMealTypes: MealType[] = [
    { id: "1", name: 'Breakfast' },
    { id: "2", name: 'Lunch' },
    { id: "3", name: 'Dinner' },
    { id: "4", name: 'Snack' },
  ];

  // Placeholder diet types
  const placeholderDietTypes: DietType[] = [
    { id: "1", name: 'Regular' },
    { id: "2", name: 'Vegetarian' },
    { id: "3", name: 'Vegan' },
    { id: "4", name: 'Gluten-Free' },
    { id: "5", name: 'Keto' },
  ];

  // Use placeholder data if API data is not available
  const mealsData = meals.length > 0 ? meals : placeholderMeals;
  const mealTypesData = mealTypes.length > 0 ? mealTypes : placeholderMealTypes;
  const dietTypesData = dietTypes.length > 0 ? dietTypes : placeholderDietTypes;
  const totalPages = Math.ceil(totalMeals > 0 ? totalMeals : 100 / perPage);

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Meals</h1>
            <p className="mt-2 text-sm text-gray-500">Manage your meal catalog and nutritional information</p>
          </div>
          <Link 
            href="/admin/meals/create" 
            className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clipRule="evenodd" />
            </svg>
            Create New Meal
          </Link>
        </div>

        {/* Search and Filter */}
        <div className="bg-white shadow rounded-lg p-6 mb-6">
          <div className="flex flex-wrap items-center gap-4">
            <form onSubmit={handleSearch} className="flex flex-1 gap-2">
              <input
                type="text"
                placeholder="Search by meal name"
                className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
              <button 
                type="submit" 
                className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Search
              </button>
            </form>
            
            <div className="flex items-center space-x-4">
              <div>
                <label htmlFor="mealTypeFilter" className="block text-sm font-medium text-gray-700 mb-1">
                  Meal Type
                </label>
                <select
                  id="mealTypeFilter"
                  className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white text-gray-900"
                  value={filterMealType}
                  onChange={handleMealTypeChange}
                >
                  <option value="" className="text-gray-500">All Types</option>
                  {mealTypesData.map((type) => (
                    <option key={type.id} value={type.id} className="text-gray-900">
                      {type.name}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label htmlFor="dietTypeFilter" className="block text-sm font-medium text-gray-700 mb-1">
                  Diet Type
                </label>
                <select
                  id="dietTypeFilter"
                  className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white text-gray-900"
                  value={filterDietType}
                  onChange={handleDietTypeChange}
                >
                  <option value="" className="text-gray-500">All Diets</option>
                  {dietTypesData.map((type) => (
                    <option key={type.id} value={type.id} className="text-gray-900">
                      {type.name}
                    </option>
                  ))}
                </select>
              </div>
            </div>
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

        {/* Meals Grid */}
        <div className="mb-6">
          {loading ? (
            <div className="flex justify-center items-center h-64">
              <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
            </div>
          ) : mealsData.length === 0 ? (
            <div className="bg-white shadow rounded-lg p-8 text-center">
              <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 className="mt-2 text-sm font-medium text-gray-900">No meals found</h3>
              <p className="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {mealsData.map((meal) => (
                <div key={meal.id} className="bg-white shadow rounded-lg overflow-hidden">
                  <div className="h-48 overflow-hidden">
                    <img 
                      src={meal.image_url || "https://via.placeholder.com/300x200?text=No+Image"} 
                      alt={meal.name} 
                      className="w-full h-full object-cover"
                    />
                  </div>
                  <div className="p-4">
                    <div className="flex justify-between items-start mb-2">
                      <h2 className="text-lg font-medium text-gray-900">{meal.name}</h2>
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        meal.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                      }`}>
                        {meal.status === 'active' ? 'Active' : 'Inactive'}
                      </span>
                    </div>
                    <div className="flex flex-wrap gap-2 mb-3">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {meal.meal_type_name}
                      </span>
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {meal.diet_type_name}
                      </span>
                    </div>
                    <div className="grid grid-cols-4 gap-2 text-sm text-gray-600 mb-4">
                      <div className="text-center">
                        <div className="font-medium">{meal.calories}</div>
                        <div className="text-xs text-gray-500">cal</div>
                      </div>
                      <div className="text-center">
                        <div className="font-medium">{meal.protein}g</div>
                        <div className="text-xs text-gray-500">protein</div>
                      </div>
                      <div className="text-center">
                        <div className="font-medium">{meal.carbs}g</div>
                        <div className="text-xs text-gray-500">carbs</div>
                      </div>
                      <div className="text-center">
                        <div className="font-medium">{meal.fat}g</div>
                        <div className="text-xs text-gray-500">fat</div>
                      </div>
                    </div>
                    <div className="flex justify-between items-center">
                      <Link 
                        href={`/admin/meals/${meal.id}`} 
                        className="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                      >
                        View Details
                      </Link>
                      <div className="flex space-x-3">
                        <button 
                          onClick={() => handleDuplicateMeal(meal.id)}
                          className="text-gray-600 hover:text-gray-900"
                        >
                          <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                            <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                          </svg>
                        </button>
                        <Link 
                          href={`/admin/meals/edit/${meal.id}`} 
                          className="text-indigo-600 hover:text-indigo-900"
                        >
                          <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                          </svg>
                        </Link>
                        <button 
                          onClick={() => handleDeleteMeal(meal.id)}
                          className="text-red-600 hover:text-red-900"
                        >
                          <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fillRule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clipRule="evenodd" />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Pagination */}
        <div className="bg-white shadow rounded-lg p-4">
          <div className="flex items-center justify-between">
            <div className="flex-1 flex justify-between sm:hidden">
              <button
                onClick={() => handlePageChange(page - 1)}
                disabled={page <= 1}
                className={`relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                  page <= 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'
                }`}
              >
                Previous
              </button>
              <button
                onClick={() => handlePageChange(page + 1)}
                disabled={page >= totalPages}
                className={`ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                  page >= totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700 hover:bg-gray-50'
                }`}
              >
                Next
              </button>
            </div>
            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p className="text-sm text-gray-700">
                  Showing <span className="font-medium">{(page - 1) * perPage + 1}</span> to{' '}
                  <span className="font-medium">{Math.min(page * perPage, totalMeals || 100)}</span> of{' '}
                  <span className="font-medium">{totalMeals || 100}</span> results
                </p>
              </div>
              <div>
                <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button
                    onClick={() => handlePageChange(page - 1)}
                    disabled={page <= 1}
                    className={`relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 text-sm font-medium ${
                      page <= 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-500 hover:bg-gray-50'
                    }`}
                  >
                    <span className="sr-only">Previous</span>
                    <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fillRule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clipRule="evenodd" />
                    </svg>
                  </button>
                  
                  {/* Page numbers */}
                  {[...Array(totalPages > 0 ? totalPages : 10)].map((_, i) => {
                    const pageNum = i + 1;
                    if (
                      pageNum === 1 ||
                      pageNum === totalPages ||
                      (pageNum >= page - 2 && pageNum <= page + 2)
                    ) {
                      return (
                        <button
                          key={i}
                          onClick={() => handlePageChange(pageNum)}
                          className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                            page === pageNum
                              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                          }`}
                        >
                          {pageNum}
                        </button>
                      );
                    } else if (
                      (pageNum === page - 3 && pageNum > 1) ||
                      (pageNum === page + 3 && pageNum < totalPages)
                    ) {
                      return (
                        <span
                          key={i}
                          className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700"
                        >
                          ...
                        </span>
                      );
                    }
                    return null;
                  })}
                  
                  <button
                    onClick={() => handlePageChange(page + 1)}
                    disabled={page >= totalPages}
                    className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 text-sm font-medium ${
                      page >= totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-500 hover:bg-gray-50'
                    }`}
                  >
                    <span className="sr-only">Next</span>
                    <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                    </svg>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
} 