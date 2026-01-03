@echo off
echo Creating dump directory...
if not exist "docker\dumps" mkdir "docker\dumps"

echo Dumping Databases to docker/dumps/...
echo.

echo 1. Dumping Geo Service (Cities)...
docker-compose -f docker-compose.prod.yml exec -T postgres pg_dump -U postgres --clean --if-exists db_geo > docker/dumps/geo_dump.sql

echo 2. Dumping Identity Service (Users)...
docker-compose -f docker-compose.prod.yml exec -T postgres pg_dump -U postgres --clean --if-exists db_identity > docker/dumps/identity_dump.sql

echo 3. Dumping Tenant Service (Merchants)...
docker-compose -f docker-compose.prod.yml exec -T postgres pg_dump -U postgres --clean --if-exists db_tenant > docker/dumps/tenant_dump.sql

echo 4. Dumping Order Service...
docker-compose -f docker-compose.prod.yml exec -T postgres pg_dump -U postgres --clean --if-exists db_oms > docker/dumps/oms_dump.sql

echo.
echo DUMP COMPLETE! 
echo Please commit these files to git:
echo   git add docker/dumps/*.sql
echo   git commit -m "Chore: Add local DB dumps for migration"
echo   git push
pause
