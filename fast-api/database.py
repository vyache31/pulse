import aiomysql
from dotenv import load_dotenv
import os

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "port": int(os.getenv("DB_PORT", 3306)),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASSWORD"),
    "db": os.getenv("DB_NAME"),
    "charset": os.getenv("DB_CHARSET", "utf8mb4"),
}

async def get_db():
    return await aiomysql.connect(**DB_CONFIG)

async def db_query(sql: str, *args):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(sql, args)
        result = await cur.fetchall()
    conn.close()
    return result

async def db_query_one(sql: str, *args):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(sql, args)
        result = await cur.fetchone()
    conn.close()
    return result

async def db_execute(sql: str, *args):
    conn = await get_db()
    async with conn.cursor() as cur:
        await cur.execute(sql, args)
        await conn.commit()
    conn.close()

async def db_insert(sql: str, *args):
    conn = await get_db()
    async with conn.cursor() as cur:
        await cur.execute(sql, args)
        await conn.commit()
        return cur.lastrowid
    conn.close()
