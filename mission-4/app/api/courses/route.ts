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

export async function GET() {
  try {
    const client = await pool.connect();
    const result = await client.query('SELECT * FROM courses ORDER BY id ASC');
    client.release();
    return NextResponse.json(result.rows);
  } catch (error) {
    console.error(error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const payload = await verifyToken(request);
  if (!payload || payload.role !== 'admin') {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
  }

  const { name, sks, description } = await request.json();
  if (!name || !sks) {
    return NextResponse.json({ message: 'Name and SKS are required' }, { status: 400 });
  }

  try {
    const client = await pool.connect();
    const result = await client.query(
      'INSERT INTO courses (name, sks, description) VALUES ($1, $2, $3) RETURNING *',
      [name, sks, description || null]
    );
    client.release();
    return NextResponse.json(result.rows[0], { status: 201 });
  } catch (error) {
    console.error(error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
