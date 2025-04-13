'use client';

import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import WorkoutForm from '@/app/admin/workouts/workout-form';

export default function NewWorkoutPage() {
  return (
    <div className="container mx-auto py-6">
      <Card>
        <CardHeader>
          <CardTitle>Create New Workout Routine</CardTitle>
        </CardHeader>
        <CardContent>
          <WorkoutForm />
        </CardContent>
      </Card>
    </div>
  );
} 