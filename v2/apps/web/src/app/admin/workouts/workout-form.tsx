'use client';

import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { WorkoutRoutineDetail } from '../../../../types/workout';

interface WorkoutFormProps {
  routineId?: string;
}

export default function WorkoutForm({ routineId }: WorkoutFormProps) {
  const router = useRouter();
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [weeks, setWeeks] = useState(4);
  const [levelId, setLevelId] = useState('');
  const [coachId, setCoachId] = useState('');

  const routine = useQuery(
    api.workouts.getWorkoutRoutineById,
    routineId ? { id: routineId } : 'skip'
  ) as WorkoutRoutineDetail | null;

  const levels = useQuery(api.workouts.getWorkoutLevels) || [];
  const coaches = useQuery(api.workouts.getWorkoutCoaches) || [];

  const createRoutine = useMutation(api.workouts.createWorkoutRoutine);
  const updateRoutine = useMutation(api.workouts.updateWorkoutRoutine);

  useEffect(() => {
    if (routine) {
      setName(routine.name);
      setDescription(routine.description || '');
      setWeeks(routine.weeks);
      setLevelId(routine.levelId);
      setCoachId(routine.coachId || '');
    }
  }, [routine]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    try {
      if (routineId) {
        await updateRoutine({
          id: routineId,
          name,
          description,
          weeks,
          levelId,
          coachId: coachId || null,
        });
      } else {
        await createRoutine({
          name,
          description,
          weeks,
          levelId,
          coachId: coachId || null,
        });
      }
      router.push('/admin/workouts');
    } catch (error) {
      console.error('Error saving workout routine:', error);
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>{routineId ? 'Edit Workout Routine' : 'New Workout Routine'}</CardTitle>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <Label htmlFor="name">Name</Label>
            <Input
              id="name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
            />
          </div>

          <div>
            <Label htmlFor="description">Description</Label>
            <Textarea
              id="description"
              value={description}
              onChange={(e) => setDescription(e.target.value)}
            />
          </div>

          <div>
            <Label htmlFor="weeks">Duration (weeks)</Label>
            <Input
              id="weeks"
              type="number"
              min="1"
              value={weeks}
              onChange={(e) => setWeeks(parseInt(e.target.value))}
              required
            />
          </div>

          <div>
            <Label htmlFor="level">Level</Label>
            <select
              id="level"
              value={levelId}
              onChange={(e) => setLevelId(e.target.value)}
              className="w-full p-2 border rounded"
              required
            >
              <option value="">Select a level</option>
              {levels.map((level) => (
                <option key={level.id} value={level.id}>
                  {level.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <Label htmlFor="coach">Coach (optional)</Label>
            <select
              id="coach"
              value={coachId}
              onChange={(e) => setCoachId(e.target.value)}
              className="w-full p-2 border rounded"
            >
              <option value="">No coach</option>
              {coaches.map((coach) => (
                <option key={coach.id} value={coach.id}>
                  {coach.name}
                </option>
              ))}
            </select>
          </div>

          <div className="flex justify-end">
            <Button type="submit">
              {routineId ? 'Update Routine' : 'Create Routine'}
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  );
} 