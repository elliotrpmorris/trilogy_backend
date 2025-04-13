'use client';

import React from 'react';
import ExerciseForm from '../exercise-form';

export default function NewExercisePage() {
  return (
    <div className="container mx-auto py-10">
      <div className="mb-6">
        <h1 className="text-3xl font-bold">Add New Exercise</h1>
      </div>
      <ExerciseForm />
    </div>
  );
} 