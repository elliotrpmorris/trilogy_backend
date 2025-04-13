'use client';

import React, { useState } from 'react';
import { useQuery, useMutation } from 'convex/react';
import { api } from '../../../../../convex/_generated/api';
import { Button } from '@/components/ui/button';
import { DataTable } from '@/components/ui/data-table';
import { useRouter } from 'next/navigation';
import { Plus, Edit, Trash } from 'lucide-react';
import { toast } from 'sonner';

export default function WorkoutTypesPage() {
  const router = useRouter();
  const workoutTypes = useQuery(api.physio.listWorkoutTypes);
  const deleteWorkoutType = useMutation(api.physio.deleteWorkoutType);

  const [isLoading, setIsLoading] = useState(false);

  const handleDelete = async (id: string) => {
    if (window.confirm('Are you sure you want to delete this workout type?')) {
      try {
        await deleteWorkoutType({ id });
        toast.success('Workout type deleted successfully');
      } catch (error) {
        toast.error('Failed to delete workout type');
      }
    }
  };

  const columns = [
    {
      accessorKey: 'physio_type',
      header: 'Type',
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
            onClick={() => router.push(`/admin/physio/types/${row.original._id}`)}
          >
            <Edit className="h-4 w-4" />
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
        <h1 className="text-3xl font-bold">Workout Types</h1>
        <Button onClick={() => router.push('/admin/physio/types/new')}>
          <Plus className="mr-2 h-4 w-4" />
          Add New Type
        </Button>
      </div>

      <DataTable
        columns={columns}
        data={workoutTypes || []}
        isLoading={isLoading}
      />
    </div>
  );
} 