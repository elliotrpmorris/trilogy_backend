'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import convexAdapter from '@/lib/convexAdapter';
import Image from 'next/image';

// Types
interface WorkoutCoach {
  id: string;
  name: string;
  bio: string;
  specialties: string[];
  profileImage?: string;
  order: number;
  createdAt: number;
  updatedAt: number;
}

export default function WorkoutCoachesPage() {
  const router = useRouter();
  
  const [coaches, setCoaches] = useState<WorkoutCoach[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  
  // Form state
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingId, setEditingId] = useState<string | null>(null);
  const [formData, setFormData] = useState({
    name: '',
    bio: '',
    specialties: [] as string[],
    profileImage: '',
    order: 0
  });
  const [newSpecialty, setNewSpecialty] = useState('');

  // Fetch coaches on component mount
  useEffect(() => {
    fetchCoaches();
  }, []);

  const fetchCoaches = async () => {
    setLoading(true);
    setError('');
    
    try {
      const response = await convexAdapter.workouts.getCoaches();
      
      if (response.success) {
        setCoaches(response.data);
      } else {
        setError(response.message || 'Failed to load coaches');
      }
    } catch (err) {
      console.error('Coaches fetch error:', err);
      setError('An error occurred while loading coaches');
    } finally {
      setLoading(false);
    }
  };

  const openCreateForm = () => {
    setFormData({
      name: '',
      bio: '',
      specialties: [],
      profileImage: '',
      order: coaches.length + 1
    });
    setEditingId(null);
    setIsFormOpen(true);
  };

  const openEditForm = (coach: WorkoutCoach) => {
    setFormData({
      name: coach.name,
      bio: coach.bio || '',
      specialties: coach.specialties || [],
      profileImage: coach.profileImage || '',
      order: coach.order || 0
    });
    setEditingId(coach.id);
    setIsFormOpen(true);
  };

  const closeForm = () => {
    setIsFormOpen(false);
    setEditingId(null);
    setFormData({
      name: '',
      bio: '',
      specialties: [],
      profileImage: '',
      order: 0
    });
    setNewSpecialty('');
  };

  const handleFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === 'order' ? parseInt(value) || 0 : value
    }));
  };

  const handleSpecialtyAdd = () => {
    if (newSpecialty.trim() && !formData.specialties.includes(newSpecialty.trim())) {
      setFormData(prev => ({
        ...prev,
        specialties: [...prev.specialties, newSpecialty.trim()]
      }));
      setNewSpecialty('');
    }
  };

  const handleSpecialtyRemove = (specialty: string) => {
    setFormData(prev => ({
      ...prev,
      specialties: prev.specialties.filter(s => s !== specialty)
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
        // Update existing coach
        response = await convexAdapter.workouts.updateCoach(editingId, formData);
      } else {
        // Create new coach
        response = await convexAdapter.workouts.createCoach(formData);
      }
      
      if (response.success) {
        fetchCoaches();
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
    if (window.confirm('Are you sure you want to delete this coach? This will affect all associated workouts.')) {
      try {
        const response = await convexAdapter.workouts.deleteCoach(id);
        
        if (response.success) {
          fetchCoaches();
        } else {
          setError(response.message || 'Failed to delete coach');
        }
      } catch (err) {
        console.error('Error deleting coach:', err);
        setError('An error occurred while deleting the coach');
      }
    }
  };

  // Placeholder data for development
  const placeholderCoaches: WorkoutCoach[] = [
    {
      id: "1",
      name: 'Sarah Johnson',
      bio: 'Certified personal trainer with 10 years of experience in strength training and HIIT',
      specialties: ['Strength Training', 'HIIT', 'Weight Loss'],
      profileImage: '/images/coaches/sarah.jpg',
      order: 1,
      createdAt: Date.now(),
      updatedAt: Date.now()
    },
    {
      id: "2",
      name: 'Mike Chen',
      bio: 'Former professional athlete turned fitness coach, specializing in sports performance',
      specialties: ['Sports Performance', 'Agility Training', 'Endurance'],
      profileImage: '/images/coaches/mike.jpg',
      order: 2,
      createdAt: Date.now(),
      updatedAt: Date.now()
    }
  ];

  // Use placeholder data if API data is not available
  const coachesData = coaches.length > 0 ? coaches : placeholderCoaches;

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Workout Coaches</h1>
            <p className="mt-2 text-sm text-gray-500">Manage your team of fitness coaches and their specialties</p>
          </div>
          <button
            onClick={openCreateForm}
            className="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clipRule="evenodd" />
            </svg>
            Add Coach
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
                  {editingId ? 'Edit Coach' : 'Add Coach'}
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
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="bio">
                      Bio
                    </label>
                    <textarea
                      id="bio"
                      name="bio"
                      value={formData.bio}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      rows={3}
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="profileImage">
                      Profile Image URL
                    </label>
                    <input
                      type="text"
                      id="profileImage"
                      name="profileImage"
                      value={formData.profileImage}
                      onChange={handleFormChange}
                      className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="https://example.com/image.jpg"
                    />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                      Specialties
                    </label>
                    <div className="flex space-x-2">
                      <input
                        type="text"
                        value={newSpecialty}
                        onChange={(e) => setNewSpecialty(e.target.value)}
                        className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Add specialty"
                      />
                      <button
                        type="button"
                        onClick={handleSpecialtyAdd}
                        className="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                      >
                        Add
                      </button>
                    </div>
                    <div className="mt-2 flex flex-wrap gap-2">
                      {formData.specialties.map((specialty) => (
                        <span
                          key={specialty}
                          className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                        >
                          {specialty}
                          <button
                            type="button"
                            onClick={() => handleSpecialtyRemove(specialty)}
                            className="ml-1.5 inline-flex text-indigo-600 hover:text-indigo-900"
                          >
                            ×
                          </button>
                        </span>
                      ))}
                    </div>
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

        {/* Coaches Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {coachesData.map((coach) => (
            <div key={coach.id} className="bg-white rounded-lg shadow overflow-hidden">
              <div className="relative h-48 w-full">
                {coach.profileImage ? (
                  <Image
                    src={coach.profileImage}
                    alt={coach.name}
                    fill
                    className="object-cover"
                  />
                ) : (
                  <div className="h-full w-full bg-gray-200 flex items-center justify-center">
                    <svg className="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                )}
              </div>
              <div className="p-6">
                <h3 className="text-lg font-medium text-gray-900">{coach.name}</h3>
                <p className="mt-2 text-sm text-gray-500">{coach.bio}</p>
                <div className="mt-4 flex flex-wrap gap-2">
                  {coach.specialties?.map((specialty) => (
                    <span
                      key={specialty}
                      className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                    >
                      {specialty}
                    </span>
                  ))}
                </div>
                <div className="mt-6 flex justify-end space-x-3">
                  <button
                    onClick={() => openEditForm(coach)}
                    className="text-sm font-medium text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(coach.id)}
                    className="text-sm font-medium text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
} 