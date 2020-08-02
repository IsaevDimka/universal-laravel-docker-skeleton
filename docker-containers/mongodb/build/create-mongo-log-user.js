db.createUser(
    {
        user: "mongo_log_user",
        pwd: "_jC4Tcc@m7wKicpJ",
        roles: [
            { role: "readWrite", db: "laravel_logs" }
        ]
    });