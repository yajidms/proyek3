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

export async function GET(request: Request) {
  const payload = await verifyToken(request);
  if (!payload) {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
  }

  try {
    const client = await pool.connect();
    const result = await client.query(
      `SELECT c.id, c.name, c.sks, c.description 
       FROM enrollments e
       JOIN courses c ON e.course_id = c.id
       WHERE e.user_id = $1`,
      [payload.userId]
    );
    client.release();
    return NextResponse.json(result.rows);
  } catch (error) {
    console.error(error);
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const payload = await verifyToken(request);
  if (!payload) {
    return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
  }

  const { course_id } = await request.json();
  if (!course_id) {
    return NextResponse.json({ message: 'Course ID is required' }, { status: 400 });
  }

  try {
    const client = await pool.connect();
    const check = await client.query('SELECT * FROM enrollments WHERE user_id = $1 AND course_id = $2', [payload.userId, course_id]);
    if (check.rows.length > 0) {
        client.release();
        return NextResponse.json({ message: 'Already enrolled in this course' }, { status: 409 });
    }

    const result = await client.query(
      'INSERT INTO enrollments (user_id, course_id) VALUES ($1, $2) RETURNING *',
      [payload.userId, course_id]
    );
    client.release();
    return NextResponse.json(result.rows[0], { status: 201 });
  } catch (error) {
    console.error(error);
    if ((error as any).code === '23505') {
        return NextResponse.json({ message: 'Already enrolled in this course' }, { status: 409 });
    }
    return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
  }
}
