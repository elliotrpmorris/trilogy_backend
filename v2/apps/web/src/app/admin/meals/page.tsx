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
  const updateQueryParams = (params: Record<string, string>) => {
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
    setFilterMealType(e.target.value);
    updateQueryParams({ meal_type_id: e.target.value, page: '1' });
  };

  // Handle diet type filter change
  const handleDietTypeChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setFilterDietType(e.target.value);
    updateQueryParams({ diet_type_id: e.target.value, page: '1' });
  };

  // Handle page change
  const handlePageChange = (newPage: number) => {
    setPage(newPage);
    updateQueryParams({ page: newPage.toString() });
  };

  // Fetch meal types and diet types on component mount
  useEffect(() => {
    const fetchFilters = async () => {
      try {
        const [mealTypesResponse, dietTypesResponse] = await Promise.all([
          convexAdapter.meals.getMealTypes(),
          convexAdapter.meals.getDietTypes()
        ]);
        
        if (mealTypesResponse.success) {
          setMealTypes(mealTypesResponse.data);
        }
        
        if (dietTypesResponse.success) {
          setDietTypes(dietTypesResponse.data);
        }
      } catch (err) {
        console.error('Error fetching filters:', err);
      }
    };

    fetchFilters();
  }, []);

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
        
        // Fetch meals from Convex via adapter
        const response = await convexAdapter.meals.getAll({
          page: currentPage,
          per_page: perPage,
          search: search,
          meal_type_id: mealTypeId,
          diet_type_id: dietTypeId
        });
        
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
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Meals</h1>
        <Link href="/admin/meals/create" className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Create New Meal
        </Link>
      </div>

      {/* Search and Filter */}
      <div className="bg-white p-4 rounded-lg shadow mb-6">
        <div className="flex flex-wrap items-center gap-4">
          <form onSubmit={handleSearch} className="flex flex-1 gap-2">
            <input
              type="text"
              placeholder="Search by meal name"
              className="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Search
            </button>
          </form>
          
          <div className="flex items-center">
            <label htmlFor="mealTypeFilter" className="mr-2">Meal Type:</label>
            <select
              id="mealTypeFilter"
              className="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={filterMealType}
              onChange={handleMealTypeChange}
            >
              <option value="">All Types</option>
              {mealTypesData.map((type) => (
                <option key={type.id} value={type.id}>{type.name}</option>
              ))}
            </select>
          </div>

          <div className="flex items-center">
            <label htmlFor="dietTypeFilter" className="mr-2">Diet Type:</label>
            <select
              id="dietTypeFilter"
              className="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={filterDietType}
              onChange={handleDietTypeChange}
            >
              <option value="">All Diets</option>
              {dietTypesData.map((type) => (
                <option key={type.id} value={type.id}>{type.name}</option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {/* Error Message */}
      {error && (
        <div className="bg-red-100 p-4 rounded text-red-700 mb-4">
          {error}
        </div>
      )}

      {/* Meals Grid */}
      <div className="mb-6">
        {loading ? (
          <div className="flex justify-center items-center h-64">
            <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
          </div>
        ) : mealsData.length === 0 ? (
          <div className="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <p>No meals found matching your criteria</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {mealsData.map((meal) => (
              <div key={meal.id} className="bg-white rounded-lg shadow overflow-hidden">
                <div className="h-48 overflow-hidden">
                  <img 
                    src={meal.image_url || "https://via.placeholder.com/300x200?text=No+Image"} 
                    alt={meal.name} 
                    className="w-full h-full object-cover"
                  />
                </div>
                <div className="p-4">
                  <div className="flex justify-between items-start mb-2">
                    <h2 className="text-xl font-semibold">{meal.name}</h2>
                    <span className={`px-2 py-1 text-xs rounded-full ${
                      meal.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }`}>
                      {meal.status === 'active' ? 'Active' : 'Inactive'}
                    </span>
                  </div>
                  <div className="text-sm text-gray-600 mb-2">
                    <span className="mr-2 bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{meal.meal_type_name}</span>
                    <span className="bg-purple-100 text-purple-800 px-2 py-1 rounded-full">{meal.diet_type_name}</span>
                  </div>
                  <div className="flex justify-between text-sm text-gray-600 mb-4">
                    <span>{meal.calories} cal</span>
                    <span>P: {meal.protein}g</span>
                    <span>C: {meal.carbs}g</span>
                    <span>F: {meal.fat}g</span>
                  </div>
                  <div className="flex justify-between mt-4">
                    <Link href={`/admin/meals/${meal.id}`} className="text-blue-600 hover:text-blue-800">
                      View Details
                    </Link>
                    <div className="flex space-x-3">
                      <button 
                        onClick={() => handleDuplicateMeal(meal.id)}
                        className="text-gray-600 hover:text-gray-800"
                      >
                        Duplicate
                      </button>
                      <Link href={`/admin/meals/edit/${meal.id}`} className="text-green-600 hover:text-green-800">
                        Edit
                      </Link>
                      <button 
                        onClick={() => handleDeleteMeal(meal.id)}
                        className="text-red-600 hover:text-red-800"
                      >
                        Delete
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
      <div className="flex items-center justify-between bg-white p-4 rounded-lg shadow">
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
                <span>←</span>
              </button>
              
              {/* Page numbers */}
              {[...Array(totalPages > 0 ? totalPages : 10)].map((_, i) => {
                // Show 5 page buttons max
                if (totalPages > 7) {
                  // Always show first, last, and current page
                  // For others, show 2 before and 2 after current page
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
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
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
                }
                
                // Show all pages if total pages <= 7
                return (
                  <button
                    key={i}
                    onClick={() => handlePageChange(i + 1)}
                    className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                      page === i + 1
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    }`}
                  >
                    {i + 1}
                  </button>
                );
              })}
              
              <button
                onClick={() => handlePageChange(page + 1)}
                disabled={page >= totalPages}
                className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 text-sm font-medium ${
                  page >= totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-500 hover:bg-gray-50'
                }`}
              >
                <span className="sr-only">Next</span>
                <span>→</span>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  );
} 