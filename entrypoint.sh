#!/bin/sh
#wait for db up
echo "Waiting for the database to be ready..."
while ! nc -z $DB_HOST $DB_PORT; do
  sleep 1
done
echo "Database is ready!"
# Run database migrations
php artisan migrate:fresh

echo "Seeding the database... Please wait, this may take some time"

# Fetch news articles
php artisan app:fetch-news-articles

echo "Database seeding completed!"
# Execute the main container command
exec "$@"