<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard</title>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="font-bold text-xl text-gray-800">My Dashboard</h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded transition duration-200">
                ចាកចេញ (Logout)
            </button>
        </form>
    </nav>

    <div class="container mx-auto mt-10 p-5">
        <div class="bg-white p-8 rounded-lg shadow-lg border-l-4 border-green-500">
            <h2 class="text-2xl text-green-600 font-bold mb-4">Login ជោគជ័យ!</h2>
            <p class="text-gray-700 text-lg">
                សូមស្វាគមន៍, <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-gray-500 mt-2">អ៊ីមែលរបស់អ្នកគឺ: {{ Auth::user()->email }}</p>
        </div>
    </div>

</body>
</html>