'use client';

import React from 'react';
import { useParams } from 'next/navigation';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ProgramForm } from '@/app/admin/physio/programs/program-form';
import { Id } from '../../../../../../convex/_generated/dataModel';

export default function EditProgramPage() {
  const params = useParams();
  const programId = params.id as Id<"physioPrograms">;

  return (
    <div className="container mx-auto py-6">
      <Card>
        <CardHeader>
          <CardTitle>Edit Physio Program</CardTitle>
        </CardHeader>
        <CardContent>
          <ProgramForm programId={programId} />
        </CardContent>
      </Card>
    </div>
  );
} 