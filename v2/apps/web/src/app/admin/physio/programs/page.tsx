'use client';

import React from 'react';
import Link from 'next/link';
import { useQuery } from 'convex/react';
import { api } from '@/convex/_generated/api';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-react';
import { PhysioProgram } from '@/types/physio';

export default function ProgramsPage() {
  const programs = useQuery(api.physio.listPrograms) as PhysioProgram[] | undefined;

  return (
    <div className="container mx-auto py-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Physio Programs</h1>
        <Link href="/admin/physio/programs/new">
          <Button>
            <Plus className="mr-2 h-4 w-4" />
            New Program
          </Button>
        </Link>
      </div>

      <div className="grid gap-4">
        {programs?.map((program) => (
          <Card key={program._id}>
            <CardHeader>
              <CardTitle>{program.name}</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="text-gray-600 mb-2">{program.description}</p>
              <p className="text-sm text-gray-500">
                Duration: {program.weeks} weeks
              </p>
              <div className="mt-4">
                <Link href={`/admin/physio/programs/${program._id}`}>
                  <Button variant="outline">Edit Program</Button>
                </Link>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  );
} 