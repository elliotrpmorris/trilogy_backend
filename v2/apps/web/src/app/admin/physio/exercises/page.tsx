'use client';

import React, { useState } from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { Button } from '../../../../components/ui/button';
import { DataTable } from '../../../../components/ui/data-table';
import { useRouter } from 'next/navigation';
import { Plus, Edit, Trash, Copy } from 'lucide-react';
import { toast } from 'sonner';

export default function PhysioExercisesPage() {
  const router = useRouter();
  const exercises = useQuery(api.physio.listExercises);
  const deleteExercise = useMutation(api.physio.deleteExercise);
  const duplicateExercise = useMutation(api.physio.duplicateExercise);

  const [isLoading, setIsLoading] = useState(false);

  const handleDelete = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this exercise?')) {
      try {
        await deleteExercise({ id });
        toast.success('Exercise deleted successfully');
      } catch (error) {
        toast.error('Failed to delete exercise');
      }
    }
  };

  const handleDuplicate = async (id: string) => {
    try {
      await duplicateExercise({ id });
      toast.success('Exercise duplicated successfully');
    } catch (error) {
      toast.error('Failed to duplicate exercise');
    }
  };

  const columns = [
    {
      accessorKey: 'title',
      header: 'Title',
    },
    {
      accessorKey: 'program_name',
      header: 'Program',
    },
    {
      accessorKey: 'week_no',
      header: 'Week',
    },
    {
      accessorKey: 'rep',
      header: 'Reps',
    },
    {
      accessorKey: 'sets',
      header: 'Sets',
    },
    {
      accessorKey: 'worktime',
      header: 'Duration (min)',
    },
    {
      accessorKey: 'status',
      header: 'Status',
      cell: ({ row }: any) => (
        <span className={row.original.status === 'Y' ? 'text-green-500' : 'text-red-500'}>
          {row.original.status === 'Y' ? 'Active' : 'Inactive'}
        </span>
      ),
    },
    {
      id: 'actions',
      cell: ({ row }: any) => (
        <div className="flex space-x-2">
          <Button
            variant="ghost"
            size="icon"
            onClick={() => router.push(`/admin/physio/exercises/${row.original._id}`)}
          >
            <Edit className="h-4 w-4" />
          </Button>
          <Button
            variant="ghost"
            size="icon"
            onClick={() => handleDuplicate(row.original._id)}
          >
            <Copy className="h-4 w-4" />
          </Button>
          <Button
            variant="ghost"
            size="icon"
            onClick={() => handleDelete(row.original._id)}
          >
            <Trash className="h-4 w-4" />
          </Button>
        </div>
      ),
    },
  ];

  return (
    <div className="container mx-auto py-10">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Physio Exercises</h1>
        <Button onClick={() => router.push('/admin/physio/exercises/new')}>
          <Plus className="mr-2 h-4 w-4" />
          Add New Exercise
        </Button>
      </div>

      <DataTable
        columns={columns}
        data={exercises || []}
        isLoading={isLoading}
      />
    </div>
  );
} 