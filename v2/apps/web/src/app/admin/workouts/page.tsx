'use client';

import React from 'react';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DataTable } from '@/components/ui/data-table';
import { ColumnDef } from '@tanstack/react-table';
import { useQuery } from 'convex/react';
import { api } from '../../../../convex/_generated/api';
import { WorkoutRoutine } from '../../../../convex/types/workout';

const columns: ColumnDef<WorkoutRoutine>[] = [
  {
    accessorKey: 'name',
    header: 'Name',
  },
  {
    accessorKey: 'description',
    header: 'Description',
  },
  {
    accessorKey: 'weeks',
    header: 'Duration (weeks)',
  },
  {
    accessorKey: 'workoutLevelId',
    header: 'Level',
  },
  {
    accessorKey: 'coachId',
    header: 'Coach',
  },
  {
    id: 'actions',
    cell: ({ row }: { row: { original: WorkoutRoutine } }) => {
      const routine = row.original;
      const router = useRouter();

      return (
        <div className="flex space-x-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => router.push(`/admin/workouts/${routine._id}`)}
          >
            View
          </Button>
          <Button
            variant="outline"
            size="sm"
            onClick={() => router.push(`/admin/workouts/${routine._id}/edit`)}
          >
            Edit
          </Button>
        </div>
      );
    },
  },
];

export default function WorkoutsPage() {
  const router = useRouter();
  const workouts = useQuery(api.workouts.getWorkoutRoutines, {});

  return (
    <div className="container mx-auto py-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Workout Routines</h1>
        <Button onClick={() => router.push('/admin/workouts/new')}>
          Add New Workout
        </Button>
      </div>
      <Card>
        <CardHeader>
          <CardTitle>All Workout Routines</CardTitle>
        </CardHeader>
        <CardContent>
          <DataTable columns={columns} data={workouts || []} />
        </CardContent>
      </Card>
    </div>
  );
} 