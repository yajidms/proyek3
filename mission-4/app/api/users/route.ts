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
    if (!payload || payload.role !== 'admin') {
        return NextResponse.json({ message: 'Unauthorized' }, { status: 403 });
    }

    try {
        const client = await pool.connect();
        const result = await client.query('SELECT id, name, email, role FROM users');
        client.release();
        return NextResponse.json(result.rows);
    } catch (error) {
        console.error(error);
        return NextResponse.json({ message: 'Internal server error' }, { status: 500 });
    }
}
