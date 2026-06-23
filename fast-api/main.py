from contextlib import asynccontextmanager
import asyncio
import redis.asyncio as redis
import json
from fastapi import FastAPI, Depends, Request
from fastapi.middleware.cors import CORSMiddleware
from routers import ws
from database import db_execute
from datetime import datetime

from auth import get_current_user

async def redis_subscriber():
    while True:
        try:
            client = redis.from_url(
                'redis://127.0.0.1:6379',
                socket_connect_timeout=5,
                socket_keepalive=True,
                socket_timeout=None,
                decode_responses=False
            )
            pubsub = client.pubsub()
            await pubsub.subscribe('new_task', 'updated_task', 'deleted_task')
            print('SUB for new_task, updated_task, deleted_task')
            
            async for message in pubsub.listen():
                if message['type'] != 'message':
                    continue
                print("receive message")
                channel = message['channel'].decode()
                data = json.loads(message['data'])
                await ws.manager.broadcast({
                        'type': channel,
                        'post': data
                    })
        except asyncio.CancelledError:
            print("Subscriber cancelled")
            break
        except Exception as e:
            print(f"Subscriber error: {e}")
            await asyncio.sleep(3)
            continue
        finally:
            try:
                await client.close()
            except:
                pass

@asynccontextmanager
async def lifespan(app: FastAPI):
    task = asyncio.create_task(redis_subscriber())
    yield
    task.cancel()

app = FastAPI(title='Pulse API', version='0.5.0', lifespan=lifespan)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["https://pulse.vyache.space"],
    allow_credentials=True,
    allow_methods=["DELETE", "GET", "OPTIONS", "PATCH", "POST", "PUT"],
    allow_headers=["*"],
    expose_headers=["*"],
    max_age=86400,
)

app.include_router(ws.router)

@app.get('/api/status')
async def status():
    return {'status': 'ok', 'time': str(datetime.now())}
  
@app.get('/api/users')
async def get_users(
	user = Depends(get_current_user)
):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(
            'SELECT id, username, email, created_at FROM users'
        )
        users = await cur.fetchall()
    conn.close()
    for u in users:
        u['created_at'] = str(u['created_at'])
    return {'users': users, 'count': len(users)}
