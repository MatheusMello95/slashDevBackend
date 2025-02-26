## 🚀 Configuration and Execution
### **1️⃣ Clone the repository**
```
git clone https://github.com/MatheusMello95/slashDevBackend.git
cd slashDevBackend
```
### **2️⃣ Install dependencies**
```
npm install && npm run build
composer install
```
### **3️⃣ Configure the environment**
Create a .env file based on .env.example and configure the database connection.
```
cp .env.example .env
```

After copying the contents of .env.example to .env, configure the database with the following information:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slashdev
DB_USERNAME=root
DB_PASSWORD=
```
### **4️⃣ Run migrations and seeders**
```
php artisan migrate --seed
```
### **5️⃣ Start the server**
```
php artisan serve
```
Laravel will display the address where the server is running, usually `http://127.0.0.1:8000`.
