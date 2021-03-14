db.createUser(
    {
        user: "mongo_laravel_user",
        pwd: "_jC4Tcc@m7wKicpJ",
        roles: [
            { role: "readWrite", db: "laravel_logs" }
        ]
    }
);

db.createUser(
    {
        user: "mongo_roadrunner_user",
        pwd: "oo43wxiiFD1zi6rK",
        roles: [
            { role: "readWrite", db: "roadrunner_logs" }
        ]
    }
);