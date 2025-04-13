'use client';

import React from 'react';
import { useParams } from 'next/navigation';
import { useQuery } from 'convex/react';
import { api } from '@/convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { useRouter } from 'next/navigation';

export default function WorkoutDetailPage() {
  const params = useParams();
  const router = useRouter();
  const routineId = params.id as string;
  const workout = useQuery(api.workouts.getWorkoutRoutine, { id: routineId });

  if (!workout) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container mx-auto py-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">{workout.name}</h1>
        <div className="flex space-x-2">
          <Button
            variant="outline"
            onClick={() => router.push(`/admin/workouts/${routineId}/edit`)}
          >
            Edit Workout
          </Button>
          <Button onClick={() => router.push('/admin/workouts')}>
            Back to List
          </Button>
        </div>
      </div>

      <div className="grid gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Workout Details</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid gap-4">
              <div>
                <h3 className="font-semibold">Description</h3>
                <p className="text-gray-600">{workout.description}</p>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <h3 className="font-semibold">Duration</h3>
                  <p className="text-gray-600">{workout.duration} minutes</p>
                </div>
                <div>
                  <h3 className="font-semibold">Level</h3>
                  <p className="text-gray-600">{workout.level}</p>
                </div>
                <div>
                  <h3 className="font-semibold">Coach</h3>
                  <p className="text-gray-600">{workout.coach}</p>
                </div>
                <div>
                  <h3 className="font-semibold">Status</h3>
                  <p className="text-gray-600">{workout.status}</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Exercises</CardTitle>
          </CardHeader>
          <CardContent>
            {workout.exercises?.length > 0 ? (
              <div className="grid gap-4">
                {workout.exercises.map((exercise, index) => (
                  <div key={index} className="border rounded-lg p-4">
                    <h3 className="font-semibold">{exercise.name}</h3>
                    <p className="text-gray-600">{exercise.description}</p>
                    <div className="grid grid-cols-2 gap-4 mt-2">
                      <div>
                        <p className="text-sm text-gray-500">Sets: {exercise.sets}</p>
                        <p className="text-sm text-gray-500">Reps: {exercise.reps}</p>
                      </div>
                      <div>
                        <p className="text-sm text-gray-500">Rest: {exercise.rest} seconds</p>
                        <p className="text-sm text-gray-500">Equipment: {exercise.equipment}</p>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-gray-600">No exercises added yet.</p>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  );
} 