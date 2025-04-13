'use client';

import React from 'react';
import { useParams } from 'next/navigation';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import WorkoutForm from '@/app/admin/workouts/workout-form';

export default function EditWorkoutPage() {
  const params = useParams();
  const routineId = params.id as string;

  return (
    <div className="container mx-auto py-6">
      <Card>
        <CardHeader>
          <CardTitle>Edit Workout Routine</CardTitle>
        </CardHeader>
        <CardContent>
          <WorkoutForm routineId={routineId} />
        </CardContent>
      </Card>
    </div>
  );
} 