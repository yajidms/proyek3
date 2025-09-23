import { NextResponse } from 'next/server';
import { Pool } from 'pg';
import { jwtVerify } from 'jose';

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
});

const secret = new TextEncoder().encode(process.env.JWT_SECRET || 'your-secret-key');

async function verifyToken(request: Request) {
  const token = request.headers.get('authorization')?.split(' ')[1];
  if (!token) return null;
  try {
    const { payload } = await jwtVerify(token, secret);
    return payload as { userId: number; role: string };
  } catch (error) {
    return null;
  }
}

export async function PUT(request: Request, { params }: { params: { id: string } }) {
  const payload = await verifyToken(request);
  if (!payload || payload.role !== 'admin') {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
  }

  const { id } = params;
  const { name, sks, description } = await request.json();

  if (!name || !sks) {
    return NextResponse.json({ message: 'Name and SKS are required' }, { status: 400 });
  }

  try {
    const client = await pool.connect();
    const result = await client.query(
      'UPDATE courses SET name = $1, sks = $2, description = $3, updated_at = CURRENT_TIMESTAMP WHERE id = $4 RETURNING *',
      [name, sks, description || null, id]
    );
    client.release();

    if (result.rows.length === 0) {
      return NextResponse.json({ message: 'Course not found' }, { status: 404 });
    }

    return NextResponse.json(result.rows[0]);
  } catch (error) {
    console.error(error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request: Request, { params }: { params: { id: string } }) {
  const payload = await verifyToken(request);
  if (!payload || payload.role !== 'admin') {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
  }

  const { id } = params;

  try {
    const client = await pool.connect();
    const result = await client.query('DELETE FROM courses WHERE id = $1 RETURNING *', [id]);
    client.release();

    if (result.rows.length === 0) {
      return NextResponse.json({ message: 'Course not found' }, { status: 404 });
    }

    return NextResponse.json({ message: 'Course deleted successfully' });
  } catch (error) {
    console.error(error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
