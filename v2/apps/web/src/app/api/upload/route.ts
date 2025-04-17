import { NextResponse } from 'next/server';

export async function POST(request: Request) {
  try {
    const formData = await request.formData();
    const file = formData.get('file') as File;

    if (!file) {
      return NextResponse.json(
        { error: 'No file provided' },
        { status: 400 }
      );
    }

    // Convert file to base64
    const buffer = await file.arrayBuffer();
    const base64Data = Buffer.from(buffer).toString('base64');

    // Upload to Convex storage using HTTP API
    const response = await fetch(`${process.env.NEXT_PUBLIC_CONVEX_URL}/uploadFile`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: file.name,
        contentType: file.type,
        data: base64Data,
      }),
    });

    if (!response.ok) {
      throw new Error('Failed to upload to Convex');
    }

    const result = await response.json();

    return NextResponse.json({
      success: true,
      url: result.url,
      storageId: result.storageId,
    });
  } catch (error) {
    console.error('Upload error:', error);
    return NextResponse.json(
      { error: 'Failed to upload file' },
      { status: 500 }
    );
  }
} 