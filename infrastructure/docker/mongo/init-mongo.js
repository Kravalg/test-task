db.auth('admin', 'admin')

db = db.getSiblingDB('test')

db.createUser({
    user: 'test',
    pwd: 'test',
    roles: [
        {
            role: 'root',
            db: 'test',
        },
    ],
});