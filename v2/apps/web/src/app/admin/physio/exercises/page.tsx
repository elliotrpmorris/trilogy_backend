'use client';

import React from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { Button } from '../../../../components/ui/button';
import { DataTable } from '../../../../components/ui/data-table';
import { useRouter } from 'next/navigation';
import { Plus, Edit, Trash, Copy, Image as ImageIcon } from 'lucide-react';
import { toast } from 'sonner';
import { Id } from '../../../../../convex/_generated/dataModel';
import { ColumnDef } from '@tanstack/react-table';
import Image from 'next/image';

type Exercise = {
  _id: Id<'physioExercises'>;
  _creationTime: number;
  title: string;
  description?: string;
  instructions?: string;
  image?: string;
  video_url?: string;
  program_name: string;
  week_no: number;
  rep?: number;
  sets?: number;
  worktime?: number;
  equipments?: string;
  musclegroup?: string;
  status: 'Y' | 'N';
  created_by: Id<'users'>;
  createdAt: number;
  updatedAt: number;
};

export default function PhysioExercisesPage() {
  const router = useRouter();
  const exercises = useQuery(api.physio.listExercises, {});
  const deleteExercise = useMutation(api.physio.deleteExercise);
  const duplicateExercise = useMutation(api.physio.duplicateExercise);

  const handleDelete = async (id: Id<'physioExercises'>) => {
    if (window.confirm('Are you sure you want to delete this exercise?')) {
      try {
        await deleteExercise({ id });
        toast.success('Exercise deleted successfully');
      } catch (error) {
        toast.error('Failed to delete exercise');
      }
    }
  };

  const handleDuplicate = async (id: Id<'physioExercises'>) => {
    try {
      await duplicateExercise({ id });
      toast.success('Exercise duplicated successfully');
    } catch (error) {
      toast.error('Failed to duplicate exercise');
    }
  };

  const formatDate = (timestamp: number) => {
    return new Date(timestamp).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const columns: ColumnDef<Exercise>[] = [
    {
      id: 'serial',
      header: 'Sl. No.',
      cell: ({ row }) => row.index + 1,
    },
    {
      id: 'image',
      header: 'Image',
      cell: ({ row }) => (
        <div className="w-16 h-16 relative">
          {row.original.image ? (
            <Image
              src={row.original.image}
              alt={row.original.title}
              fill
              className="object-cover rounded"
            />
          ) : (
            <div className="w-full h-full flex items-center justify-center bg-gray-100 rounded">
              <ImageIcon className="w-6 h-6 text-gray-400" />
            </div>
          )}
        </div>
      ),
    },
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
      accessorKey: 'updatedAt',
      header: 'Last Updated',
      cell: ({ row }) => formatDate(row.original.updatedAt),
    },
    {
      accessorKey: 'status',
      header: 'Status',
      cell: ({ row }) => (
        <span className={row.original.status === 'Y' ? 'text-green-500' : 'text-red-500'}>
          {row.original.status === 'Y' ? 'Active' : 'Inactive'}
        </span>
      ),
    },
    {
      id: 'actions',
      cell: ({ row }) => (
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
      />
    </div>
  );
} 